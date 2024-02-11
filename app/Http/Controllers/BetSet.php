<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\Auction;
use App\Models\BetHistory;
use Illuminate\Support\Facades\DB;

class BetSet extends Controller
{
    public function UpBet(Request $request)
    {
        $token = $request->cookie('access_token');
        if(!$token){
            return response()->json([
                'errors' => "Invalid token.",
                'success' => false,
            ], 401);
        }

        $userId = decrypt($token);

        try{
            $validatedData = $request->validate([
                'auc_id' => 'required|integer',
                'amount' => 'required|numeric',
            ]);
        

            $auction = Auction::findOrFail($validatedData['auc_id']);

            
            if ($validatedData['amount'] <= $auction->last_bet) {
                return response()->json([
                    'errors' => "Your bid must be higher than the current highest bid.",
                    'success' => false,
                ], 400);
            }

            DB::beginTransaction();

            $auction->last_bet = $validatedData['amount'];
            $auction->auc_winner = $userId;
            $auction->save();

            BetHistory::create([
                'user_id' => $userId,
                'auc_id' => $auction->id,
                'amount' => $validatedData['amount'],
            ]);

            DB::commit();

            return response()->json([
                'msg' => 'Bid placed successfully!',
                'success' => true,
            ], 200);

        }catch (ValidationException $e) {
            return response()->json([
                'error' => $e->errors(),
                'success' => false,
            ], 422);
        }
    }
}

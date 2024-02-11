<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Auction;
class GetAuction extends Controller
{
    public function getShortList(Request $request)
    {
        $token = $request->cookie('access_token');
        if(!$token){
            return response()->json([
                'errors' => "Invalid token.",
                'success' => false,
            ], 401);
        }

        $shortList = Auction::select('id', 'title', 'start_price', 'auc_end_time', 'images')->paginate(20);

        return response()->json([
            'data' => $shortList,
            'success' => true
        ], 200);
    }



    public function getMyAuctions(Request $request)
    {
        $token = $request->cookie('access_token');
        if(!$token){
            return response()->json([
                'errors' => "Invalid token.",
                'success' => false,
            ], 401);
        }

        $userId = decrypt($token);

        $shortList = Auction::where('user_id', $userId)->select('id', 'title', 'start_price', 'auc_end_time', 'images')->paginate(20);

        return response()->json([
            'data' => $shortList,
            'success' => true
        ], 200);
    }

    public function getAuctionById(Request $request)
    {
        try {
            $auctionId = $request->query('auc_id');

            if (!$auctionId) {
                return response()->json([
                    'error' => 'Auction ID is required.',
                    'success' => false,
                ], 400);
            }

            $auction = Auction::findOrFail($auctionId);

            return response()->json([
                'data' => $auction,
                'success' => true,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Auction not found.',
                'success' => false,
            ], 404);
        }
    }
}

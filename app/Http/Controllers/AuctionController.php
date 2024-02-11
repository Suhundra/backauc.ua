<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\Auction;
use Carbon\Carbon;

class AuctionController extends Controller
{
    public function createAuc(Request $request)
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
                'title' => 'required|string',
                'start_price' => 'required|string',
                'description' => 'required|string',
                'images' => 'required|string',
                'duration' => 'required|date_format:H:i',
            ]);
        }catch (ValidationException $e) {
            return response()->json([
                'error' => $e->errors(),
                'success' => false,
            ], 422);
        }

        $auction = Auction::create([
            'user_id' => $userId,
            'title' => $validatedData['title'],
            'start_price' => $validatedData['start_price'],
            'last_bet' => $validatedData['start_price'],
            'status' => true,
            'description' => $validatedData['description'],
            'images' => $validatedData['images'],
            'auc_end_time' => Carbon::now()->addHours(Carbon::createFromFormat('H:i', $validatedData['duration'])->hour)->addMinutes(Carbon::createFromFormat('H:i', $validatedData['duration'])->minute),
        ]);

        if($auction){
            return response()->json([
                'msg' => 'The auction added successfully!', 
                'success' => true
            ], 200);
        }else{
            return response()->json([
                'msg' => 'The auction not added.', 
                'success' => false
            ], 400);
        }
    }


    public function updateAuction(Request $request)
    {
        $token = $request->cookie('access_token');
        if(!$token){
            return response()->json([
                'errors' => "Invalid token.",
                'success' => false,
            ], 401);
        }

        $userId = decrypt($token);

        try {
            $validatedData = $request->validate([
                'id' => 'required|integer',
                'title' => 'string',
                'start_price' => 'string',
                'status' => 'string',
                'description' => 'string',
                'images' => 'string',
                'duration' => 'date_format:H:i',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => $e->errors(),
                'success' => false,
            ], 422);
        }

        $auction = Auction::where('id', $validatedData['id'])->where('user_id', $userId)->first();

        if(!$auction) {
            return response()->json([
                'msg' => 'Auction not found for the given user.',
                'success' => false
            ], 404);
        }

        if($auction->user_id != $userId) {
            return response()->json([
                'msg' => 'You are not authorized to update this auction.',
                'success' => false
            ], 403);
        }

        $auction->title = isset($validatedData['title']) ? $validatedData['title'] : $auction->title;
        $auction->start_price = isset($validatedData['start_price']) ? $validatedData['start_price'] : $auction->start_price;
        $auction->status = isset($validatedData['status']) ? $validatedData['status'] : $auction->status;
        $auction->description = isset($validatedData['description']) ? $validatedData['description'] : $auction->description;
        $auction->images = isset($validatedData['images']) ? $validatedData['images'] : $auction->images;
        $auction->auc_end_time = isset($validatedData['duration']) ? Carbon::now()->addHours(Carbon::createFromFormat('H:i', $validatedData['duration'])->hour)->addMinutes(Carbon::createFromFormat('H:i', $validatedData['duration'])->minute) : $auction->auc_end_time;

        $auction->save();

        return response()->json([
            'msg' => 'Auction updated successfully!',
            'success' => true
        ], 200);
    }

    public function deleteAuction(Request $request)
    {
        $token = $request->cookie('access_token');
        if(!$token){
            return response()->json([
                'errors' => "Invalid token.",
                'success' => false,
            ], 401);
        }

        $userId = decrypt($token);

        try {
            $validatedData = $request->validate([
                'id' => 'required|integer',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => $e->errors(),
                'success' => false,
            ], 422);
        }

        $auction = Auction::where('id', $validatedData['id'])->first();

        if(!$auction) {
            return response()->json([
                'msg' => 'Auction not found.',
                'success' => false
            ], 404);
        }

        if($auction->user_id != $userId) {
            return response()->json([
                'msg' => 'You are not authorized to delete this auction.',
                'success' => false
            ], 403);
        }

        $auction->delete();

        return response()->json([
            'msg' => 'Auction deleted successfully!',
            'success' => true
        ], 200);
    }

    
}

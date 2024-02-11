<?php

namespace App\Http\Controllers;
use App\Models\BetHistory;
use Illuminate\Http\Request;

class GetHistory extends Controller
{
    public function getAuctionHistory(Request $request)
{
    try {
        $auctionId = $request->query('auc_id');

        if (!$auctionId) {
            return response()->json([
                'error' => 'Auction ID is required.',
                'success' => false,
            ], 400);
        }

        $auctionHistory = BetHistory::where('auc_id', $auctionId)->get();

        return response()->json([
            'data' => $auctionHistory,
            'success' => true,
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Auction history not found.',
            'success' => false,
        ], 404);
    }
}
}

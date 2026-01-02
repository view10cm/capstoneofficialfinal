<?php

namespace App\Http\Controllers;

use App\Models\StaffToKitchenTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KitchenController extends Controller
{
    public function dashboard()
    {
        // Fetch all orders from the transaction table
        $transactions = StaffToKitchenTransaction::all();
        
        // Group transactions by orderID
        $groupedOrders = [];
        
        foreach ($transactions as $transaction) {
            $orderID = $transaction->orderID;
            
            if (!isset($groupedOrders[$orderID])) {
                $groupedOrders[$orderID] = [];
            }
            
            $groupedOrders[$orderID][] = $transaction;
        }
        
        // Add status field to each group based on cookingStatus
        foreach ($groupedOrders as $orderID => &$orderGroup) {
            // Determine the overall order status based on cookingStatus
            // Use the first item's cookingStatus as representative for the entire order
            $firstItem = $orderGroup[0];
            
            if (isset($firstItem->cookingStatus)) {
                // Map cookingStatus to frontend status
                $cookingStatus = $firstItem->cookingStatus;
                
                if ($cookingStatus === 'In Progress') {
                    $orderGroup[0]->status = 'pending';
                } elseif ($cookingStatus === 'Cooking') {
                    $orderGroup[0]->status = 'preparing';
                } elseif ($cookingStatus === 'Product Ready') {
                    $orderGroup[0]->status = 'ready';
                } elseif ($cookingStatus === 'Completed') {
                    $orderGroup[0]->status = 'completed';
                } else {
                    $orderGroup[0]->status = 'pending'; // Default
                }
            } else {
                $orderGroup[0]->status = 'pending'; // Default status
                $orderGroup[0]->cookingStatus = 'In Progress'; // Default cooking status
            }
        }
        
        return view('kitchenLandingPage', compact('groupedOrders'));
    }
    
    /**
     * Update cooking status for an order
     */
    public function updateCookingStatus(Request $request)
    {
        $request->validate([
            'orderID' => 'required|string',
            'cookingStatus' => 'required|string|in:In Progress,Cooking,Product Ready,Completed'
        ]);
        
        try {
            // Update all items with the same orderID
            $updated = StaffToKitchenTransaction::where('orderID', $request->orderID)
                ->update(['cookingStatus' => $request->cookingStatus]);
            
            return response()->json([
                'success' => true,
                'message' => 'Cooking status updated successfully',
                'updatedCount' => $updated
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update cooking status: ' . $e->getMessage()
            ], 500);
        }
    }
}
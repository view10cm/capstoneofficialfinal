<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderToStaffTransaction;
use Illuminate\Support\Facades\DB;

class StaffController extends Controller
{
    /**
     * Get all orders for the staff dashboard
     */
    public function getOrders(Request $request)
    {
        try {
            // Get orders from the last 24 hours with status not 'Product Voided'
            $orders = OrderToStaffTransaction::where('orderProductStatus', '!=', 'Product Voided')
                ->where('orderCreateDateAndTime', '>=', now()->subDay())
                ->orderBy('orderCreateDateAndTime', 'desc')
                ->get();
            
            return response()->json($orders);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch orders',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Update status of a specific product in an order
     */
    public function updateStatus(Request $request)
    {
        try {
            $validated = $request->validate([
                'orderID' => 'required|string',
                'productName' => 'required|string',
                'status' => 'required|in:For Payment,In Progress,Completed,To Follow-up,Product Voided'
            ]);
            
            // Find and update the specific product in the order
            $updated = OrderToStaffTransaction::where('orderID', $validated['orderID'])
                ->where('orderProductName', $validated['productName'])
                ->update(['orderProductStatus' => $validated['status']]);
            
            if ($updated) {
                return response()->json([
                    'success' => true,
                    'message' => 'Status updated successfully'
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Order product not found'
            ], 404);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to update status',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Update status of all products in an order
     */
    public function updateAllStatus(Request $request)
    {
        try {
            $validated = $request->validate([
                'orderID' => 'required|string',
                'status' => 'required|in:For Payment,In Progress,Completed,To Follow-up,Product Voided'
            ]);
            
            // Update all products in the order
            $updated = OrderToStaffTransaction::where('orderID', $validated['orderID'])
                ->update(['orderProductStatus' => $validated['status']]);
            
            if ($updated) {
                return response()->json([
                    'success' => true,
                    'message' => 'All items status updated successfully',
                    'updated_count' => $updated
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to update status',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Cancel an order
     */
    public function cancelOrder(Request $request)
    {
        try {
            $validated = $request->validate([
                'orderID' => 'required|string'
            ]);
            
            // Mark all products in the order as "To Follow-up" (which can represent cancelled)
            $updated = OrderToStaffTransaction::where('orderID', $validated['orderID'])
                ->update(['orderProductStatus' => 'To Follow-up']);
            
            if ($updated) {
                return response()->json([
                    'success' => true,
                    'message' => 'Order cancelled successfully',
                    'updated_count' => $updated
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to cancel order',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Void an order
     */
    public function voidOrder(Request $request)
    {
        try {
            $validated = $request->validate([
                'orderID' => 'required|string'
            ]);
            
            // Mark all products in the order as "Product Voided"
            $updated = OrderToStaffTransaction::where('orderID', $validated['orderID'])
                ->update(['orderProductStatus' => 'Product Voided']);
            
            if ($updated) {
                return response()->json([
                    'success' => true,
                    'message' => 'Order voided successfully',
                    'updated_count' => $updated
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to void order',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
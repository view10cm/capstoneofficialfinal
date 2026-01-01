<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StaffOrderController extends Controller
{
    // Get all orders for staff display
    public function getOrders()
    {
        try {
            // Changed from 'orders' to 'order_to_staff_transaction'
            $orders = DB::table('order_to_staff_transaction')
                ->select([
                    'orderID',
                    'paymentNumber',
                    'orderCreateDateAndTime',
                    'orderType',
                    'orderPaymentMethod',
                    'orderProductName',
                    'orderQuantity',
                    'orderTotalProductPrice',
                    'orderProductStatus'  // Changed from 'orderStatus' to 'orderProductStatus'
                ])
                ->where('orderProductStatus', '!=', 'Product Voided')  // Only get non-voided orders
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

    // Update order status
    public function updateAllStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'orderID' => 'required|string',
            'paymentNumber' => 'required|string',
            'status' => 'required|string',
            'selectedItems' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $validator->errors()
            ], 422);
        }

        try {
            $data = $validator->validated();
            
            // Update order status in database
            DB::table('order_to_staff_transaction')
                ->where('orderID', $data['orderID'])
                ->where('paymentNumber', $data['paymentNumber'])
                ->update(['orderProductStatus' => $data['status']]);

            return response()->json([
                'success' => true,
                'message' => 'Order status updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to update order status',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Cancel order
    public function cancelOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'orderID' => 'required|string',
            'paymentNumber' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $validator->errors()
            ], 422);
        }

        try {
            $data = $validator->validated();
            
            // Update order status to cancelled
            DB::table('order_to_staff_transaction')
                ->where('orderID', $data['orderID'])
                ->where('paymentNumber', $data['paymentNumber'])
                ->update(['orderProductStatus' => 'To Follow-up']);

            return response()->json([
                'success' => true,
                'message' => 'Order cancelled successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to cancel order',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Void products
    public function voidProducts(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'orderID' => 'required|string',
            'paymentNumber' => 'required|string',
            'items' => 'required|array',
            'items.*' => 'string',
            'status' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $validator->errors()
            ], 422);
        }

        try {
            $data = $validator->validated();
            
            // Update orderProductStatus to "Product Voided" for each product
            $updatedCount = 0;
            foreach ($data['items'] as $productName) {
                $result = DB::table('order_to_staff_transaction')
                    ->where('orderID', $data['orderID'])
                    ->where('paymentNumber', $data['paymentNumber'])
                    ->where('orderProductName', $productName)
                    ->update(['orderProductStatus' => 'Product Voided']);
                
                if ($result) {
                    $updatedCount++;
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Products marked as Product Voided',
                'voided_count' => $updatedCount
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to void products',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
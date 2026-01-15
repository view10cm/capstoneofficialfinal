<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\StaffToKitchenTransaction;

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
                    'orderNotes',
                    'orderProductStatus'
                ])
                ->where('orderProductStatus', 'For Payment')  // Only show "For Payment" orders
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
            'selectedItems' => 'nullable|array',
            'referenceNumber' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $validator->errors()
            ], 422);
        }

        try {
            $data = $validator->validated();
            
            // If selectedItems is provided, only update those specific items
            if (!empty($data['selectedItems']) && is_array($data['selectedItems'])) {
                // Get all products for this order
                $products = DB::table('order_to_staff_transaction')
                    ->where('orderID', $data['orderID'])
                    ->where('paymentNumber', $data['paymentNumber'])
                    ->get();
                
                $updatedCount = 0;
                foreach ($data['selectedItems'] as $itemIndex) {
                    if (isset($products[$itemIndex])) {
                        $product = $products[$itemIndex];
                        DB::table('order_to_staff_transaction')
                            ->where('orderID', $data['orderID'])
                            ->where('paymentNumber', $data['paymentNumber'])
                            ->where('orderProductName', $product->orderProductName)
                            ->update(['orderProductStatus' => $data['status']]);
                        $updatedCount++;
                    }
                }
                
                return response()->json([
                    'success' => true,
                    'message' => 'Selected items status updated successfully',
                    'updated_count' => $updatedCount
                ]);
            } else {
                // Update all products in the order
                DB::table('order_to_staff_transaction')
                    ->where('orderID', $data['orderID'])
                    ->where('paymentNumber', $data['paymentNumber'])
                    ->update(['orderProductStatus' => $data['status']]);

                return response()->json([
                    'success' => true,
                    'message' => 'All items status updated successfully'
                ]);
            }
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
        'paymentNumber' => 'required|string',
        'adminPassword' => 'required|string'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'error' => 'Validation failed',
            'messages' => $validator->errors()
        ], 422);
    }

    try {
        $data = $validator->validated();
        
        // Verify admin password
        $adminUser = DB::table('users')
            ->where('role', 'Admin')
            ->first();
        
        if (!$adminUser) {
            return response()->json([
                'error' => 'No admin user found',
                'message' => 'Cannot verify admin password'
            ], 403);
        }
        
        // Verify password (using Laravel's Hash::check)
        if (!Hash::check($data['adminPassword'], $adminUser->password)) {
            return response()->json([
                'error' => 'Invalid admin password',
                'message' => 'The provided admin password is incorrect'
            ], 401);
        }
        
        // Update order status to "Cancelled" 
        // Note: Make sure "Cancelled" is in your order_to_staff_transaction table's orderProductStatus enum
        $updated = DB::table('order_to_staff_transaction')
            ->where('orderID', $data['orderID'])
            ->where('paymentNumber', $data['paymentNumber'])
            ->update(['orderProductStatus' => 'Cancelled']);

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
        \Log::error('Error cancelling order: ' . $e->getMessage());
        \Log::error('Stack trace: ' . $e->getTraceAsString());
        
        return response()->json([
            'error' => 'Failed to cancel order',
            'message' => $e->getMessage(),
            'details' => 'Check if "Cancelled" is in the orderProductStatus enum values'
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
            'status' => 'required|string',
            'adminPassword' => 'required|string' // Add password validation
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $validator->errors()
            ], 422);
        }

        try {
            $data = $validator->validated();
            
            // Verify admin password
            $adminUser = DB::table('users')
                ->where('role', 'Admin')
                ->first();
            
            if (!$adminUser) {
                return response()->json([
                    'error' => 'No admin user found',
                    'message' => 'Cannot verify admin password'
                ], 403);
            }
            
            // Verify password (using Laravel's Hash::check)
            if (!Hash::check($data['adminPassword'], $adminUser->password)) {
                return response()->json([
                    'error' => 'Invalid admin password',
                    'message' => 'The provided admin password is incorrect'
                ], 401);
            }
            
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

    /**
     * Save payment transaction to staff_to_kitchen_transaction table
     */
    public function savePaymentTransaction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'orderID' => 'required|string',
            'paymentNumber' => 'required|string',
            'orderType' => 'required|string',
            'paymentMethod' => 'required|string',
            'products' => 'required|array',
            'products.*.name' => 'required|string',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.unitPrice' => 'required|numeric|min:0',
            'products.*.totalPrice' => 'required|numeric|min:0',
            'products.*.taxAmount' => 'required|numeric|min:0',
            'products.*.notes' => 'nullable|string',
            'amountPaid' => 'required|numeric|min:0',
            'changeAmount' => 'nullable|numeric|min:0',
            'referenceNumber' => 'nullable|string',
            'staffName' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $validator->errors()
            ], 422);
        }

        try {
            $data = $validator->validated();
            
            // Flag to track if this is the first product (for order-level notes)
            $isFirstProduct = true;
            $savedCount = 0;
            
            foreach ($data['products'] as $product) {
                StaffToKitchenTransaction::create([
                    'orderID' => $data['orderID'],
                    'paymentNumber' => (int)$data['paymentNumber'],
                    'orderType' => $data['orderType'],
                    'paymentMethod' => $data['paymentMethod'],
                    'productName' => $product['name'],
                    'quantity' => $product['quantity'],
                    'unitPrice' => $product['unitPrice'],
                    'totalPrice' => $product['totalPrice'],
                    'taxAmount' => $product['taxAmount'],
                    'productNotes' => $product['notes'] ?? null,
                    'orderNotes' => $isFirstProduct ? (null) : null, // Only save on first product, then set to null
                    'paymentStatus' => 'completed',
                    'amountPaid' => $data['amountPaid'],
                    'changeAmount' => $data['changeAmount'] ?? 0,
                    'referenceNumber' => $data['referenceNumber'] ?? null,
                    'staffName' => $data['staffName'],
                    'paymentProcessedAt' => now()
                ]);
                
                $isFirstProduct = false; // After first product, never save order notes again
                $savedCount++;
            }

            return response()->json([
                'success' => true,
                'message' => 'Payment transaction saved successfully',
                'saved_count' => $savedCount
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to save payment transaction',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getOrderTrackerData()
    {
        // Fetch all transactions from staff_to_kitchen_transaction table
        $transactions = StaffToKitchenTransaction::all();
        
        // Group by orderID and paymentNumber
        $groupedOrders = [];
        
        foreach ($transactions as $transaction) {
            $key = $transaction->orderID . '-' . $transaction->paymentNumber;
            
            if (!isset($groupedOrders[$key])) {
                // Only get order notes from first transaction (should be null or order-level notes)
                $groupedOrders[$key] = [
                    'orderID' => $transaction->orderID,
                    'paymentNumber' => $transaction->paymentNumber,
                    'orderType' => $transaction->orderType,
                    'paymentMethod' => $transaction->paymentMethod,
                    'cookingStatus' => $transaction->cookingStatus ?? 'In Progress',
                    'staffName' => $transaction->staffName,
                    'paymentProcessedAt' => $transaction->paymentProcessedAt,
                    'notes' => null, // Initialize as null - order notes are not being stored currently
                    'items' => []
                ];
            }
            
            // Add item to the order
            $groupedOrders[$key]['items'][] = [
                'productName' => $transaction->productName,
                'quantity' => $transaction->quantity,
                'unitPrice' => $transaction->unitPrice,
                'totalPrice' => $transaction->totalPrice,
                'productNotes' => $transaction->productNotes, // Only item-specific notes
                'taxAmount' => $transaction->taxAmount ?? 0
            ];
        }
        
        // Calculate total items for each order
        foreach ($groupedOrders as &$order) {
            $order['totalItems'] = count($order['items']);
        }
        
        // Convert to array
        $orders = array_values($groupedOrders);
        
        // Sort by paymentProcessedAt (newest first)
        usort($orders, function($a, $b) {
            return strtotime($b['paymentProcessedAt']) - strtotime($a['paymentProcessedAt']);
        });
        
        return response()->json([
            'success' => true,
            'orders' => $orders,
            'total' => count($orders)
        ]);
    }

    // Get available products from menu_products table
public function getAvailableProducts()
{
    try {
        $products = DB::table('menu_products')
            ->select([
                'menuID',
                'menuName',
                'menuCategory',
                'menuSubcategory',
                'menuPrice',
                'menuStatus'
            ])
            ->where('menuStatus', 'Available')
            ->orderBy('menuCategory')
            ->orderBy('menuSubcategory')
            ->orderBy('menuName')
            ->get();

        return response()->json($products);
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Failed to fetch products',
            'message' => $e->getMessage()
        ], 500);
    }
}

// Add products to existing order
public function addProductsToOrder(Request $request)
{
    $validator = Validator::make($request->all(), [
        'orderID' => 'required|string',
        'paymentNumber' => 'required|string',
        'products' => 'required|array|min:1',
        'products.*.productId' => 'required|string',
        'products.*.name' => 'required|string',
        'products.*.price' => 'required|numeric|min:0',
        'products.*.quantity' => 'required|integer|min:1'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'error' => 'Validation failed',
            'messages' => $validator->errors()
        ], 422);
    }

    try {
        $data = $validator->validated();
        
        // Verify the order exists
        $existingOrder = DB::table('order_to_staff_transaction')
            ->where('orderID', $data['orderID'])
            ->where('paymentNumber', $data['paymentNumber'])
            ->first();

        if (!$existingOrder) {
            return response()->json([
                'error' => 'Order not found',
                'message' => 'Cannot add products to non-existent order'
            ], 404);
        }

        // Check if order is cancelled
        $cancelledCheck = DB::table('order_to_staff_transaction')
            ->where('orderID', $data['orderID'])
            ->where('paymentNumber', $data['paymentNumber'])
            ->where('orderProductStatus', 'Cancelled')
            ->exists();

        if ($cancelledCheck) {
            return response()->json([
                'error' => 'Order is cancelled',
                'message' => 'Cannot add products to a cancelled order'
            ], 400);
        }

        $addedCount = 0;
        $now = now();

        foreach ($data['products'] as $product) {
            // Calculate total price
            $totalPrice = $product['price'] * $product['quantity'];
            $tax = $totalPrice * 0.12; // 12% tax rate

            // Insert new product into the order
            DB::table('order_to_staff_transaction')->insert([
                'orderID' => $data['orderID'],
                'paymentNumber' => $data['paymentNumber'],
                'orderType' => $existingOrder->orderType,
                'orderPaymentMethod' => $existingOrder->orderPaymentMethod,
                'orderProductName' => $product['name'],
                'orderQuantity' => $product['quantity'],
                'orderTotalProductPrice' => $totalPrice,
                'orderTotalProductTax' => $tax,
                'orderProductStatus' => 'For Payment',
                'orderNotes' => null,
                'orderCreateDateAndTime' => $now
            ]);

            $addedCount++;
        }

        return response()->json([
            'success' => true,
            'message' => 'Products added to order successfully',
            'added_count' => $addedCount
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Failed to add products to order',
            'message' => $e->getMessage()
        ], 500);
    }
}

}
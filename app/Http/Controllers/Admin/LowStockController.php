<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OverallLowStockItems;
use App\Models\Admin\Ingredient;

class LowStockController extends Controller
{
    /**
     * Get low stock items count for dashboard
     */
    public function getLowStockCount()
    {
        try {
            $lowStockData = OverallLowStockItems::getLowStockData();
            
            return response()->json([
                'success' => true,
                'data' => $lowStockData
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error in getLowStockCount: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load low stock data',
                'data' => [
                    'low_stock_count' => 0
                ]
            ], 500);
        }
    }

    /**
     * Get detailed low stock items list
     */
    public function getLowStockItems()
    {
        try {
            // Get all ingredients that are low stock
            $lowStockItems = Ingredient::where('ingredientAvailability', 'Low Stock')
                ->select('ingredientID', 'ingredientName', 'ingredientQuantity', 'ingredientUnit')
                ->orderBy('ingredientName', 'asc')
                ->get();
                
            $lowStockCount = $lowStockItems->count();
            
            // Update the overall low stock count if needed
            $overallLowStock = OverallLowStockItems::first();
            if ($overallLowStock && $overallLowStock->lowStockItemsCount != $lowStockCount) {
                $overallLowStock->update(['lowStockItemsCount' => $lowStockCount]);
            }
            
            return response()->json([
                'success' => true,
                'data' => [
                    'count' => $lowStockCount,
                    'items' => $lowStockItems
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error in getLowStockItems: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load low stock items',
                'data' => [
                    'count' => 0,
                    'items' => []
                ]
            ], 500);
        }
    }

    /**
     * Force refresh low stock count
     */
    public function refreshLowStockCount()
    {
        try {
            // Count low stock items from ingredients table
            $lowStockCount = Ingredient::where('ingredientAvailability', 'Low Stock')->count();
            
            // Update or create the overall low stock record
            $overallLowStock = OverallLowStockItems::first();
            
            if ($overallLowStock) {
                $overallLowStock->update([
                    'lowStockItemsCount' => $lowStockCount,
                    'updated_at' => now()
                ]);
            } else {
                OverallLowStockItems::create([
                    'lowStockID' => 'LOW_STOCK_001',
                    'lowStockItemsCount' => $lowStockCount,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Low stock count refreshed successfully',
                'data' => [
                    'low_stock_count' => $lowStockCount
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error in refreshLowStockCount: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to refresh low stock count'
            ], 500);
        }
    }
}
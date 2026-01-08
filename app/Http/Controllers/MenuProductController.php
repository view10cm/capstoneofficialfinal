<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OverallMenuProducts;
use App\Models\Admin\MenuProduct;

class MenuProductController extends Controller
{
    /**
     * Get menu products data for dashboard
     */
    public function getMenuProductsData()
    {
        try {
            // Get overall menu products count
            $overallMenu = OverallMenuProducts::getOverallMenuProducts();
            
            // Get active menu products count
            $activeMenuCount = MenuProduct::where('menuStatus', 'Available')->count();
            
            // Update the overall count if needed
            if (!$overallMenu || $overallMenu->overallMenu != $activeMenuCount) {
                $overallMenu = OverallMenuProducts::updateOverallMenuCount();
            }
            
            return response()->json([
                'success' => true,
                'data' => [
                    'overall_menu' => $overallMenu ? $overallMenu->overallMenu : 0,
                    'active_menu' => $activeMenuCount,
                    'updated_at' => $overallMenu ? $overallMenu->updated_at->format('Y-m-d H:i:s') : null
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error getting menu products data: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load menu products data',
                'data' => [
                    'overall_menu' => 0,
                    'active_menu' => 0,
                    'updated_at' => null
                ]
            ], 500);
        }
    }

    /**
     * Get low stock items count
     * You can customize this based on your inventory logic
     */
    public function getLowStockItemsCount()
    {
        try {
            // Example: You can query your inventory model here
            // For now, let's return 0 or implement based on your inventory system
            
            $lowStockCount = 0; // Replace with actual query when inventory is implemented
            
            return response()->json([
                'success' => true,
                'data' => [
                    'low_stock_count' => $lowStockCount
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error getting low stock items count: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load low stock items count',
                'data' => [
                    'low_stock_count' => 0
                ]
            ], 500);
        }
    }
}
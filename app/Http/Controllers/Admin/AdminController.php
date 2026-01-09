<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\IngredientsCategory;
use App\Models\Admin\Ingredient;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use App\Models\OverallSales;
use App\Models\OverallMealsServed;
use App\Models\OverallMenuProducts;
use App\Models\OverallLowStockItems; // Add this line
use App\Models\Admin\MenuProduct;

class AdminController extends Controller
{
    /**
     * Show admin dashboard
     */
    public function dashboard()
    {
        // Fetch overall sales data
        $overallSales = OverallSales::first();
        
        // If no overall sales record exists, create a default one
        if (!$overallSales) {
            $overallSales = new OverallSales();
            $overallSales->overall_sales = 0.00;
            $overallSales->created_at = now();
            $overallSales->updated_at = now();
        }
        
        // Fetch meals served data
        $mealsData = $this->getMealsServedData();
        
        // Fetch menu products data
        $menuProductsData = $this->getMenuProductsData();
        
        // Fetch low stock data
        $lowStockData = OverallLowStockItems::getLowStockData();
        
        return view('admin.dashboard', compact('overallSales', 'mealsData', 'menuProductsData', 'lowStockData'));
    }

    /**
     * Get meals served data
     */
    private function getMealsServedData()
    {
        try {
            $overallMeals = OverallMealsServed::getOverallMeals();
            $todayMeals = OverallMealsServed::getTodayMeals();
            
            return [
                'overall_meals' => $overallMeals ? $overallMeals->meals_overall : 0,
                'today_meals' => $todayMeals
            ];
            
        } catch (\Exception $e) {
            \Log::error('Error getting meals served data: ' . $e->getMessage());
            
            return [
                'overall_meals' => 0,
                'today_meals' => 0
            ];
        }
    }

    /**
     * Get menu products data
     */
    private function getMenuProductsData()
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
            
            return [
                'overall_menu' => $overallMenu ? $overallMenu->overallMenu : 0,
                'active_menu' => $activeMenuCount
            ];
            
        } catch (\Exception $e) {
            \Log::error('Error getting menu products data: ' . $e->getMessage());
            
            return [
                'overall_menu' => 0,
                'active_menu' => 0
            ];
        }
    }

    /**
     * Show inventory management page
     */
    public function inventory()
    {
        return view('admin.adminInventory');
    }

    /**
     * Show menu management page
     */
    public function menu()
    {
        return view('admin.adminMenu');
    }

    /**
     * Show users management page
     */
    public function users()
    {
        // Fetch users from database with pagination
        $users = User::orderBy('created_at', 'desc')->paginate(10);
        
        return view('admin.adminUsers', compact('users'));
    }

    /**
     * Update user status
     */
    public function updateStatus(Request $request, $user)
    {
        // Logic to update user status
        // You'll need to implement this based on your User model
    }

    /**
     * Create new user
     */
    public function createUser(Request $request)
    {
        // Logic to create new user
        // You'll need to implement this based on your User model
    }

    /**
     * Show order history page
     */
    public function orderHistory()
    {
        return view('admin.adminOrderHistory');
    }

    /**
     * Create a new category
     */
    public function createCategory(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'category_name' => 'required|string|max:255|unique:ingredients_categories,ingredientCategoryName',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            // Create the category
            $category = IngredientsCategory::create([
                'ingredientCategoryName' => $request->category_name,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Category created successfully!',
                'category' => [
                    'id' => $category->id,
                    'name' => $category->ingredientCategoryName
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create category. Please try again.'
            ], 500);
        }
    }

    /**
     * Get all categories for dropdown
     */
    public function getCategories()
    {
        try {
            $categories = IngredientsCategory::select('id', 'ingredientCategoryName')
                ->orderBy('ingredientCategoryName', 'asc')
                ->get();
                
            return response()->json([
                'success' => true,
                'categories' => $categories
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load categories'
            ], 500);
        }
    }

public function getMonthlySalesData(Request $request)
{
    try {
        // Generate last 12 months labels
        $labels = [];
        $salesData = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $labels[] = $date->format('M Y');
            
            // Generate some sample data for testing
            // Replace this with your actual database query
            $salesData[] = rand(10000, 50000);
        }
        
        // Calculate statistics
        $totalSales = array_sum($salesData);
        $averageSales = count($salesData) > 0 ? $totalSales / count($salesData) : 0;
        $maxSales = max($salesData);
        $minSales = min($salesData);
        $growthRate = count($salesData) >= 2 ? 
            (($salesData[count($salesData)-1] - $salesData[count($salesData)-2]) / $salesData[count($salesData)-2] * 100) : 0;

        return response()->json([
            'success' => true,
            'data' => [
                'labels' => $labels,
                'datasets' => [
                    [
                        'label' => 'Monthly Sales',
                        'data' => $salesData,
                        'borderColor' => '#3b82f6',
                        'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                        'tension' => 0.4
                    ]
                ],
                'statistics' => [
                    'total_sales' => $totalSales,
                    'average_sales' => $averageSales,
                    'max_sales' => $maxSales,
                    'min_sales' => $minSales,
                    'growth_rate' => $growthRate,
                    'months_count' => count($labels)
                ]
            ]
        ]);

    } catch (\Exception $e) {
        \Log::error('Error fetching monthly sales data: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Failed to load sales data',
            'error' => $e->getMessage()
        ], 500);
    }
}

}
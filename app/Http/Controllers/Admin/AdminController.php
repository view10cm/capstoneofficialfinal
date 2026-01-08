<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\IngredientsCategory;
use App\Models\Admin\Ingredient;
use App\Models\User;
use App\Models\Sales;
use App\Models\StaffToKitchenTransaction;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Show admin dashboard
     */
public function dashboard()
{
    $today = Carbon::today()->toDateString();
    
    // Get today's sales from sales table or calculate from transactions
    $todaysSales = $this->getTodaysSales($today);
    
    // Get meals served today (count of transactions)
    $mealsServed = StaffToKitchenTransaction::whereDate('paymentProcessedAt', $today)
        ->whereNotNull('paymentProcessedAt')
        ->count();
    
    // Get active orders (adjust based on your order status logic)
    $activeOrders = 0; // Placeholder - implement based on your order system
    
    // Get low stock items count (adjust based on your inventory logic)
    $lowStockCount = 0; // Placeholder - implement based on your inventory system
    
    // Get chart data
    $salesDataCurrent = $this->getCurrentMonthSales();
    $salesDataPrev = $this->getPreviousMonthSales();
    
    // CHANGE THIS LINE if your file is named adminDashboard.blade.php
    return view('adminDashboard', [ // Changed from 'admin.dashboard' to 'adminDashboard'
        'todaysSales' => $todaysSales,
        'mealsServed' => $mealsServed,
        'activeOrders' => $activeOrders,
        'lowStockCount' => $lowStockCount,
        'salesDataCurrent' => $salesDataCurrent,
        'salesDataPrev' => $salesDataPrev
    ]);
}
    
    /**
     * Get today's sales amount
     */
    private function getTodaysSales($date)
    {
        // First, check if we have today's sales in the sales table
        $salesRecord = Sales::where('date', $date)->first();
        
        if ($salesRecord) {
            return $salesRecord->today_sales;
        }
        
        // If not in sales table, calculate from transactions
        $totalSales = StaffToKitchenTransaction::whereDate('paymentProcessedAt', $date)
            ->whereNotNull('paymentProcessedAt')
            ->sum('totalPrice');
        
        // Store in sales table for future use
        $parsedDate = Carbon::parse($date);
        Sales::create([
            'date' => $date,
            'day' => $parsedDate->day,
            'month' => $parsedDate->month,
            'year' => $parsedDate->year,
            'today_sales' => $totalSales
        ]);
        
        return $totalSales;
    }
    
    /**
     * Get current month sales data for chart
     */
    private function getCurrentMonthSales()
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;
        
        // Get sales for current month from sales table
        $sales = Sales::where('month', $currentMonth)
            ->where('year', $currentYear)
            ->orderBy('day')
            ->pluck('today_sales')
            ->toArray();
        
        // If we have less than 7 days of data, pad with zeros or use transactions
        if (count($sales) < 7) {
            // Try to get more data from transactions directly
            $startDate = now()->startOfMonth();
            $endDate = now()->endOfMonth();
            
            $transactions = StaffToKitchenTransaction::whereBetween('paymentProcessedAt', [$startDate, $endDate])
                ->whereNotNull('paymentProcessedAt')
                ->selectRaw('DAY(paymentProcessedAt) as day, SUM(totalPrice) as total')
                ->groupBy('day')
                ->orderBy('day')
                ->get()
                ->pluck('total')
                ->toArray();
            
            if (!empty($transactions)) {
                return $transactions;
            }
        }
        
        // If no data, return placeholder
        if (empty($sales)) {
            return [12, 19, 3, 5, 2, 3, 20];
        }
        
        return $sales;
    }
    
    /**
     * Get previous month sales data for chart
     */
    private function getPreviousMonthSales()
    {
        $previousMonth = now()->subMonth()->month;
        $previousYear = now()->subMonth()->year;
        
        // Get sales for previous month from sales table
        $sales = Sales::where('month', $previousMonth)
            ->where('year', $previousYear)
            ->orderBy('day')
            ->pluck('today_sales')
            ->toArray();
        
        // If we have less than 7 days of data, try to get from transactions
        if (count($sales) < 7) {
            $startDate = now()->subMonth()->startOfMonth();
            $endDate = now()->subMonth()->endOfMonth();
            
            $transactions = StaffToKitchenTransaction::whereBetween('paymentProcessedAt', [$startDate, $endDate])
                ->whereNotNull('paymentProcessedAt')
                ->selectRaw('DAY(paymentProcessedAt) as day, SUM(totalPrice) as total')
                ->groupBy('day')
                ->orderBy('day')
                ->get()
                ->pluck('total')
                ->toArray();
            
            if (!empty($transactions)) {
                return $transactions;
            }
        }
        
        // If no data, return placeholder
        if (empty($sales)) {
            return [15, 12, 6, 8, 5, 8, 15];
        }
        
        return $sales;
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
}
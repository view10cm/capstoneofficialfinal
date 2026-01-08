<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\IngredientsCategory;
use App\Models\Admin\Ingredient;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use App\Models\OverallSales;

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
        
        return view('admin.dashboard', compact('overallSales'));
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
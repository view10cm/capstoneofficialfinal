<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\MenuProduct;

class CustomerController extends Controller
{
    /**
     * Display customer landing page
     */
    public function landingPage()
    {
        // Check if user is authenticated and is a customer
        if (!Auth::check() || Auth::user()->role !== 'Customer') {
            return redirect()->route('login');
        }

        return view('customer.customerLandingPage');
    }

    /**
     * Display customer dashboard
     */
    public function dashboard()
    {
        // Check if user is authenticated and is a customer
        if (!Auth::check() || Auth::user()->role !== 'Customer') {
            return redirect()->route('login');
        }

        return view('customerDashboard');
    }

    /**
     * Display customer notification page
     */
    public function notification()
    {
        // Check if user is authenticated and is a customer
        if (!Auth::check() || Auth::user()->role !== 'Customer') {
            return redirect()->route('login');
        }

        return view('customer.customerNotification');
    }

    /**
     * Display customer order area page with menu products
     */
    public function orderArea()
    {
        // Check if user is authenticated and is a customer
        if (!Auth::check() || Auth::user()->role !== 'Customer') {
            return redirect()->route('login');
        }

        // Get initial products (default: pork subcategory from main course)
        $products = MenuProduct::where('menuStatus', 'Available')
            ->where('menuCategory', 'main-course')
            ->where('menuSubcategory', 'pork')
            ->orderBy('menuName')
            ->get();
        
        return view('customer.customerOrderArea', compact('products'));
    }

    /**
     * Get products by category and subcategory (AJAX)
     */
    public function getProductsByCategory(Request $request)
    {
        $category = $request->input('category');
        $subcategory = $request->input('subcategory');

        // Map UI subcategory names to database values
        $subcategoryMap = [
            'Pork' => 'pork',
            'Chicken' => 'chicken',
            'Beef' => 'beef',
            'Fish & Seafood' => 'fish-seafood',
            'Pasta' => 'pasta',
            'Noodles' => 'noodles',
            'Salads' => 'salads',
            'Knick/Knacks' => 'knick-knacks',
            'Sandwiches' => 'sandwiches',
            'Hot' => 'hot',
            'Iced' => 'iced',
            'Frappe' => 'frappe',
            'Milktea' => 'milktea'
        ];

        $dbSubcategory = $subcategoryMap[$subcategory] ?? strtolower(str_replace(' ', '-', $subcategory));

        $products = MenuProduct::where('menuStatus', 'Available')
            ->where('menuCategory', $category)
            ->where('menuSubcategory', $dbSubcategory)
            ->orderBy('menuName')
            ->get();

        return response()->json([
            'products' => $products,
            'html' => view('partials.product-grid', compact('products'))->render()
        ]);
    }
}
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
        
        // Group products into slides of 6 items each
        $slides = $products->chunk(6);
        
        return view('customer.customerOrderArea', compact('slides', 'products'));
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

        // Group products into slides of 6 items each
        $slides = $products->chunk(6);
        
        // Get total number of slides
        $totalSlides = count($slides);
        
        // Return ALL slides data
        $slidesHtml = [];
        foreach ($slides as $slideIndex => $slideProducts) {
            $slidesHtml[] = view('partials.product-slide', [
                'products' => $slideProducts,
                'slideIndex' => $slideIndex,
                'totalSlides' => $totalSlides
            ])->render();
        }

        return response()->json([
            'products' => $products,
            'slides' => $slidesHtml,
            'totalSlides' => $totalSlides
        ]);
    }

    /**
     * Get specific slide content (for arrow navigation)
     */
    public function getSlideContent(Request $request)
    {
        $category = $request->input('category');
        $subcategory = $request->input('subcategory');
        $slideIndex = $request->input('slideIndex', 0);

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

        // Group products into slides of 6 items each
        $slides = $products->chunk(6);
        
        // Get total number of slides
        $totalSlides = count($slides);
        
        // Validate slide index
        $slideIndex = min($slideIndex, $totalSlides - 1);
        $slideIndex = max($slideIndex, 0);
        
        // Get products for requested slide
        $slideProducts = isset($slides[$slideIndex]) ? $slides[$slideIndex] : collect([]);

        return response()->json([
            'html' => view('partials.product-slide', [
                'products' => $slideProducts,
                'slideIndex' => $slideIndex,
                'totalSlides' => $totalSlides
            ])->render(),
            'slideIndex' => $slideIndex,
            'totalSlides' => $totalSlides
        ]);
    }
}
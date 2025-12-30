<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\MenuProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MenuController extends Controller
{
    /**
     * Get all menu products
     */
    public function index(Request $request)
    {
        try {
            $query = MenuProduct::query();
            
            // Search functionality
            if ($request->has('search') && !empty($request->search)) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('menuName', 'like', "%{$search}%")
                      ->orWhere('menuID', 'like', "%{$search}%")
                      ->orWhere('menuCategory', 'like', "%{$search}%")
                      ->orWhere('menuSubcategory', 'like', "%{$search}%");
                });
            }
            
            // Filter by category
            if ($request->has('category') && !empty($request->category)) {
                $query->where('menuCategory', $request->category);
            }
            
            // Order by creation date (newest first)
            $query->orderBy('created_at', 'desc');
            
            // Paginate results
            $menuProducts = $query->paginate(7);
            
            return response()->json([
                'success' => true,
                'data' => $menuProducts
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch menu items: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate next menu ID based on category
     */
    private function generateMenuID($category)
    {
        $prefix = '';
        
        switch ($category) {
            case 'main-course':
                $prefix = 'A';
                break;
            case 'appetizers':
                $prefix = 'B';
                break;
            case 'drinks':
                $prefix = 'C';
                break;
            default:
                $prefix = 'X';
        }
        
        // Find the highest menuID for this category
        $lastProduct = MenuProduct::where('menuID', 'like', $prefix . '%')
            ->orderBy('menuID', 'desc')
            ->first();
        
        if ($lastProduct) {
            $lastNumber = intval(substr($lastProduct->menuID, 1));
            $nextNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '001';
        }
        
        return $prefix . $nextNumber;
    }

    /**
     * Store a newly created menu product
     */
public function store(Request $request)
{
    // Validate the request
    $validator = Validator::make($request->all(), [
        'productName' => 'required|string|max:200',
        'productCategory' => 'required|string|in:main-course,appetizers,drinks',
        'productSubcategory' => 'required|string',
        'productPrice' => 'required|numeric|min:0|max:999999.99',
        'productImage' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
    ], [
        'productImage.image' => 'The file must be an image.',
        'productImage.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif.',
        'productImage.max' => 'The image may not be greater than 5MB.',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => $validator->errors()->first()
        ], 422);
    }

    try {
        DB::beginTransaction();

        // Generate menu ID
        $menuID = $this->generateMenuID($request->productCategory);
        
        // Handle image upload
        $imagePath = null;
if ($request->hasFile('productImage') && $request->file('productImage')->isValid()) {
    try {
        $image = $request->file('productImage');
        $filename = 'menu_' . time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
        
        // Store in storage/app/public/menu-images
        $path = $image->storeAs('public/menu-images', $filename);
        
        // Save just the relative path without 'public/'
        $imagePath = 'menu-images/' . $filename;
        
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => 'Image upload failed: ' . $e->getMessage()
        ], 500);
    }
}

        // Create the menu product
        $menuProduct = MenuProduct::create([
            'menuID' => $menuID,
            'menuName' => $request->productName,
            'menuCategory' => $request->productCategory,
            'menuSubcategory' => $request->productSubcategory,
            'menuPrice' => $request->productPrice,
            'menuStatus' => 'Available',
            'menuImage' => $imagePath,
        ]);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Menu item created successfully!',
            'data' => $menuProduct,
            'image_url' => $imagePath ? asset('storage/' . $imagePath) : null
        ]);
        
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => 'Failed to create menu item: ' . $e->getMessage()
        ], 500);
    }
}

    /**
     * Get subcategories based on category
     */
    public function getSubcategories($category)
    {
        $subcategories = [];
        
        switch ($category) {
            case 'main-course':
                $subcategories = ['pork', 'chicken', 'beef', 'fish-seafood', 'pasta', 'noodles'];
                break;
            case 'appetizers':
                $subcategories = ['knick-knacks', 'sandwiches', 'salads'];
                break;
            case 'drinks':
                $subcategories = ['hot', 'iced', 'frappe', 'milktea'];
                break;
        }
        
        return response()->json([
            'success' => true,
            'subcategories' => $subcategories
        ]);
    }

    /**
     * Update menu product status
     */
    public function updateStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:Available,Out of Stock'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            $menuProduct = MenuProduct::findOrFail($id);
            $menuProduct->update(['menuStatus' => $request->status]);

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully!'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a single menu product
     */
    public function show($id)
    {
        try {
            $menuProduct = MenuProduct::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => $menuProduct
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Menu item not found'
            ], 404);
        }
    }

    /**
     * Update a menu product
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'productName' => 'required|string|max:200',
            'productCategory' => 'required|string|in:main-course,appetizers,drinks',
            'productSubcategory' => 'required|string',
            'productPrice' => 'required|numeric|min:0|max:999999.99',
            'productImage' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            DB::beginTransaction();
            
            $menuProduct = MenuProduct::findOrFail($id);
            
            // Handle image upload if new image provided
            if ($request->hasFile('productImage') && $request->file('productImage')->isValid()) {
                // Delete old image if exists
                if ($menuProduct->menuImage) {
                    Storage::delete('public/' . $menuProduct->menuImage);
                }
                
                $image = $request->file('productImage');
                $filename = 'menu_' . time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('public/menu-images', $filename);
                $imagePath = 'menu-images/' . $filename;
            } else {
                $imagePath = $menuProduct->menuImage;
            }

            // Update the menu product
            $menuProduct->update([
                'menuName' => $request->productName,
                'menuCategory' => $request->productCategory,
                'menuSubcategory' => $request->productSubcategory,
                'menuPrice' => $request->productPrice,
                'menuImage' => $imagePath,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Menu item updated successfully!',
                'data' => $menuProduct
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update menu item: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a menu product (soft delete)
     */
    public function destroy($id)
    {
        try {
            $menuProduct = MenuProduct::findOrFail($id);
            $menuProduct->delete();

            return response()->json([
                'success' => true,
                'message' => 'Menu item deleted successfully!'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete menu item: ' . $e->getMessage()
            ], 500);
        }
    }
}
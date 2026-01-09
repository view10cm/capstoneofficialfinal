<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\MealController;
use App\Http\Controllers\Admin\MenuProductController;
use App\Http\Controllers\Admin\LowStockController; // Add this line

// Admin routes group
Route::prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    Route::get('/sales/monthly', [AdminController::class, 'getMonthlySalesData'])->name('sales.monthly');

    // Inventory
    Route::get('/inventory', [AdminController::class, 'inventory'])->name('inventory');
    
    // Inventory API routes
    Route::prefix('inventory')->group(function () {
        Route::get('/list', [InventoryController::class, 'index'])->name('inventory.list');
        Route::post('/create', [InventoryController::class, 'store'])->name('inventory.create');
        Route::get('/search', [InventoryController::class, 'search'])->name('inventory.search');
        Route::get('/export', [InventoryController::class, 'export'])->name('inventory.export');
        Route::get('/next-id', [InventoryController::class, 'getNextId'])->name('inventory.next-id');
        
        // Add these new routes for edit functionality
        Route::get('/{id}', [InventoryController::class, 'show'])->name('inventory.show');
        Route::post('/{id}/update', [InventoryController::class, 'update'])->name('inventory.update');
    });

    // Menu Routes
    Route::get('/menu', [AdminController::class, 'menu'])->name('menu');
    
    // Menu API routes
    Route::prefix('menu')->group(function () {
        Route::get('/list', [MenuController::class, 'index'])->name('menu.list');
        Route::post('/create', [MenuController::class, 'store'])->name('menu.create');
        Route::get('/subcategories/{category}', [MenuController::class, 'getSubcategories'])->name('menu.subcategories');
        Route::get('/{id}', [MenuController::class, 'show'])->name('menu.show');
        Route::post('/{id}/update', [MenuController::class, 'update'])->name('menu.update');
        Route::post('/{id}/status', [MenuController::class, 'updateStatus'])->name('menu.updateStatus');
        Route::delete('/{id}', [MenuController::class, 'destroy'])->name('menu.delete');
    });

    // Users
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    
    // Update user status
    Route::post('/users/{user}/status', [AdminController::class, 'updateStatus'])->name('users.updateStatus');
    
    // Create new user
    Route::post('/users/create', [AdminController::class, 'createUser'])->name('users.create');

    // Order History
    Route::get('/order-history', [AdminController::class, 'orderHistory'])->name('order-history');
    
    // Categories routes
    Route::get('/categories/list', [AdminController::class, 'getCategories'])->name('categories.list');
    Route::post('/categories/create', [AdminController::class, 'createCategory'])->name('categories.create');
    
    // Meals routes
    Route::prefix('meals')->group(function () {
        Route::get('/data', [MealController::class, 'getMealsData'])->name('meals.data');
        Route::post('/update', [MealController::class, 'updateMealsCount'])->name('meals.update');
    });
    
    // Menu Products routes for dashboard
    Route::prefix('menu-products')->group(function () {
        Route::get('/data', [MenuProductController::class, 'getMenuProductsData'])->name('menu-products.data');
        Route::get('/low-stock', [MenuProductController::class, 'getLowStockItemsCount'])->name('menu-products.low-stock');
    });
    
    // Low Stock routes - Add these new routes
    Route::prefix('low-stock')->group(function () {
        Route::get('/count', [LowStockController::class, 'getLowStockCount'])->name('low-stock.count');
        Route::get('/items', [LowStockController::class, 'getLowStockItems'])->name('low-stock.items');
        Route::post('/refresh', [LowStockController::class, 'refreshLowStockCount'])->name('low-stock.refresh');
    });
});
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\InventoryController;

// Admin routes group
Route::prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

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

    // Menu
    Route::get('/menu', [AdminController::class, 'menu'])->name('menu');

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
});
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;

// Admin routes group
Route::prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Inventory
    Route::get('/inventory', [AdminController::class, 'inventory'])->name('inventory');

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
    
    // Add these new routes for categories
    Route::get('/categories/list', [AdminController::class, 'getCategories'])->name('categories.list');
    Route::post('/categories/create', [AdminController::class, 'createCategory'])->name('categories.create');

});
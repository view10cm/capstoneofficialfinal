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
    
    // Update user status (for AJAX call)
    Route::post('/users/{user}/status', [AdminController::class, 'updateStatus'])->name('users.updateStatus');

    // Order History
    Route::get('/order-history', [AdminController::class, 'orderHistory'])->name('order-history');

});
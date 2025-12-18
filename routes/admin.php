<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

// Admin routes group
Route::prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', function () {
        return view('admin.adminDashboard');
    })->name('dashboard');

    // Inventory
    Route::get('/inventory', function () {
        return view('admin.adminInventory');
    })->name('inventory');

    // Menu
    Route::get('/menu', function () {
        return view('admin.adminMenu');
    })->name('menu');

    // Users
    Route::get('/users', function () {
        return view('admin.adminUsers');
    })->name('users');

    // Order History
    Route::get('/order-history', function () {
        return view('admin.adminOrderHistory');
    })->name('order-history');

});
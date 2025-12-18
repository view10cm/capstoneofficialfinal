<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StaffController;

Route::middleware(['auth', 'staff'])->group(function () {
    // Staff Landing Page/Dashboard
    Route::get('/staff/dashboard', function () {
        return view('staffLandingPage');
    })->name('staff.dashboard');
    
    Route::get('/staff/landing-page', function () {
        return view('staffLandingPage');
    })->name('staff.landingPage');
    
    // Add more staff-specific routes here
    // Route::get('/staff/orders', [StaffController::class, 'viewOrders'])->name('staff.orders');
    // Route::get('/staff/menu', [StaffController::class, 'viewMenu'])->name('staff.menu');
    // etc.
});
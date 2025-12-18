<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

// Add this route for systemDescription
Route::get('/system-description', function () {
    return view('systemDescription');
})->name('system-description');

// Login routes
Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login', [AuthController::class, 'login']);

// Admin dashboard route (protected)
Route::get('/admin/dashboard', function () {
    return view('adminDashboard');
})->name('admin.dashboard')->middleware('auth');

// Logout route
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Remove the customer/home route from here since it's in routes/customer.php
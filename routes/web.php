<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\Admin\AdminController; // Add this line

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

// Forgot Password routes
Route::get('/forgot-password', [ForgotPasswordController::class, 'show'])->name('forgot-password');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('forgot-password.send');

// Password reset routes
Route::get('/reset-password', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.reset.submit');

// Admin dashboard route - FIX THIS LINE
Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard')->middleware('auth');

// Logout route
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Add this line to include customer routes
require __DIR__.'/customer.php';

// Add this line to include admin routes
require __DIR__.'/admin.php'; // Add this line if you have an admin.php routes file
<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Add this route for systemDescription
Route::get('/system-description', function () {
    return view('systemDescription');
})->name('system-description');

// Add this route for login
Route::get('/login', function () {
    return view('login');
})->name('login');
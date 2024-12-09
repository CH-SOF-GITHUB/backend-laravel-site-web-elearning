<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// Web routes for rendering views
Route::get('/', function () {
    return view('welcome');
});

// Registration and Login Views
Route::get('/register', function () {
    return view('auth.register'); // Ensure this points to the correct view
})->name('register');

Route::get('/login', function () {
    return view('auth.login'); // Ensure this points to the correct view
})->name('login');

// Dashboard route
Route::get('/dashboard', [DashboardController::class, 'dashboard'])
    ->middleware(['auth:api', 'check.token']) // Ensure this middleware is registered and working
    ->name('dashboard');

// POST routes for registration and login
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

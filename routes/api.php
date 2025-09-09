<?php

use App\Http\Controllers\Api\V1\User\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\ProductController;



Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::post('/verifyOTP', [AuthController::class, 'verifyOTP']);

//product routes => Ahmed abdelhalim
// Route::middleware('auth:sanctum')->group(function () {        
Route::get('/products', [ProductController::class, 'index']);

// });

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // 🔹 User Profile Routes
    Route::get('/user/me', [UserController::class, 'me']);   // Get logged-in user
    Route::post('/user/update', [UserController::class, 'updateProfile']); // Update profile
});

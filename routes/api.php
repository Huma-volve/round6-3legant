<?php

use App\Http\Controllers\Api\V1\User\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;



Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::post('/verifyOTP', [AuthController::class, 'verifyOTP']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // 🔹 User Profile Routes
    Route::get('/user/me', [UserController::class, 'me']);   // Get logged-in user
    Route::post('/user/update', [UserController::class, 'updateProfile']); // Update profile
});
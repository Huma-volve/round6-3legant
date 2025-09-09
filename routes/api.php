<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\ProductController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::post('/verifyOTP', [AuthController::class, 'verifyOTP']);

//product routes => Ahmed abdelhalim
// Route::middleware('auth:sanctum')->group(function () {        
Route::get('/products', [ProductController::class, 'index']);

// });

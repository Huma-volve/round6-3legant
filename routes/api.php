<?php

use App\Http\Controllers\Category\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::post('/verifyOTP', [AuthController::class, 'verifyOTP']);

// ------- Category --------------- //

Route::controller(CategoryController::class)->group(function(){
    Route::get('index', 'index');
    Route::get('show/{catID}', 'show');
    Route::post('store', 'store');
    Route::put('update/{catID}', 'update');
    Route::delete('destroy/{catID}', 'destroy');
});


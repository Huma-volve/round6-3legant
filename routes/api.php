<?php

use App\Http\Controllers\Api\V1\Admin\Category\CategoryController;
use App\Http\Controllers\Api\V1\Admin\Product\ProductController as  AdminProductController;
use App\Http\Controllers\Api\V1\User\UserController;
use App\Http\Controllers\Api\V1\Admin\User\UserController as AdminUserController;

use App\Http\Controllers\Api\Adresses\UserLocationController;
use App\Http\Controllers\Home\HomePageController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArticleController;

use App\Http\Controllers\Api\V1\User\Wishlist\WishlistController;
use App\Http\Controllers\Api\V1\User\products\ProductController as UserProductController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/verifyOTP', [AuthController::class, 'verifyOTP']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // 🔹 User Profile Routes

    Route::get('user/me', [UserController::class, 'me']);   // Get logged-in user
    Route::post('/user/update', [UserController::class, 'updateProfile']); // Update profile
    Route::put('/user/update', [UserController::class, 'updateProfile']); // Update profile
    Route::post('/user/addresses', [UserLocationController::class, 'store']);
    Route::apiResource('user/addresses', UserLocationController::class);
    Route::get('/user/wishlist', [WishlistController::class, 'index']);
    Route::post('/user/wishlist/{productId}', [WishlistController::class, 'addProductToWishlist']);
    Route::delete('/user/wishlist/{productId}', [WishlistController::class, 'removeProductFromWishlist']);

});

Route::post('/password/forgot', [AuthController::class, 'SendResetCode']);
Route::post('/password/reset', [AuthController::class, 'updatePassword']);

//product routes user => Ahmed abdelhalim
Route::controller(UserProductController::class)->prefix('products')->group(function () {
    Route::get('/', 'index');
    Route::get('search', 'searchProducts');
});


// ------- Category admin --------------- //

Route::controller(CategoryController::class)->prefix('category')->group(function () {
    Route::get('index', 'index');
    Route::get('show/{catID}', 'show');
    Route::post('store', 'store');
    Route::put('update/{catID}', 'update');
    Route::delete('destroy/{catID}', 'destroy');
});

//----------------- Product admin --------------- //


Route::controller(AdminProductController::class)->prefix('products')->group(function () {
    Route::get('index', 'index');
    Route::get('show/{id}', 'show');
    Route::post('store', 'store');
    Route::put('update/{id}', 'update');
    Route::delete('destroy/{id}', 'destroy');
});


//----------------- User admin --------------- //
Route::prefix('admin/users')->controller(AdminUserController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('/{id}', 'show');
    Route::post('/', 'store');
    Route::put('/{id}', 'update');
    Route::delete('/{id}', 'destroy');
});

// ---------------- Home  ------------------ //

Route::controller(HomePageController::class)->prefix('home')->group(function () {
    Route::get('/categories', 'homeCategories');
    Route::get('/products/new', 'newProducts');
    Route::get('/products/most-viewed', 'mostViewedProducts');
    Route::get('/collections/featured', 'featuredCollections');
    Route::get('/products/best-sellers', 'bestSellerProducts');
});

// ---------------- Blog  ------------------ //
Route::controller(ArticleController::class)->prefix('articles')->group(function () {
    Route::get('index', 'index');
    Route::get('show/{slug}', 'show');
});

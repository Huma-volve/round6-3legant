<?php

use App\Http\Controllers\Api\V1\Admin\Category\CategoryController;
use App\Http\Controllers\Api\V1\Admin\Product\ProductController as  AdminProductController;
use App\Http\Controllers\Api\V1\User\Cart\CartController;
use App\Http\Controllers\Api\V1\User\Order\OrderController;
use App\Http\Controllers\Api\V1\User\UserController;
use App\Http\Controllers\Api\V1\Admin\User\UserController as AdminUserController;

use App\Http\Controllers\Api\V1\User\orderHistory\OrderHistoryController;
use App\Http\Controllers\Home\HomePageController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\Api\V1\User\Addresses\UserLocationController;
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

    // User Profile Routes

    Route::get('user/me', [UserController::class, 'me']);   // Get logged-in user
    
    // update profile routes
    Route::match(['post','put'], 'user/update', [UserController::class, 'updateProfile'])->name('editProfile');

    // addresses routes
    Route::apiResource('user/addresses', UserLocationController::class);

    // wishlist routes
    Route::controller(WishlistController::class)->prefix('wishlist')->group(function () {
        Route::get('/','index')->name('wishlist');
        Route::post('/{productId}','addProductToWishlist')->whereNumber('productId')->name('addProduct');
        Route::delete('/{productId}','removeProductFromWishlist')->whereNumber('productId')->name('removeProduct');
});
    Route::get('/user/ordersHistory', [OrderHistoryController::class, 'index'])->name('orderHistory');

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


// ---------------- cart --------------//


Route::controller(CartController::class)->prefix('cart')->group(function () {
        Route::get('/', 'index');              
        Route::post('/', 'store');             
        Route::put('/{item}', 'update');       
        Route::patch('/{item}/increment', 'increment'); 
        Route::patch('/{item}/decrement', 'decrement'); 
        Route::delete('/{item}', 'destroy');   
        Route::delete('/', 'clear');          
    });

//---------------- order ----------------//


Route::prefix('orders')->controller(OrderController::class)->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::get('/{id}', 'show');
    Route::post('/{id}/cancel', 'cancel');
});


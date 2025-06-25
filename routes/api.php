<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoriesController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\api\CouponController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PayLaterController;
use App\Http\Controllers\Api\ProfileController;

// Public Routes (No Authentication Required)
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/app_version', [AuthController::class, 'app_version']);
Route::get('/pincode_access', [AuthController::class, 'pincode_access']);

// ✅ Routes accessible by both guest and authenticated users
Route::middleware(['api.optional'])->group(function () {
    Route::get('/home', [HomeController::class, 'index']);
    Route::get('/categories', [CategoriesController::class, 'index']);
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/product_search', [ProductController::class, 'product_search']);
    Route::get('/product-details', [ProductController::class, 'details']);
});


// 🔒 Authenticated-only Routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/coupon-valid', [HomeController::class, 'is_coupon_valid']);
    Route::post('/update_notification_token', [HomeController::class, 'update_notification_token']);
    Route::get('test-notification', [HomeController::class, 'send_test_notification']);

    Route::get('/profile', [ProfileController::class, 'index']);
    Route::post('/update_profile', [ProfileController::class, 'update_profile']);
    Route::post('/update_address', [ProfileController::class, 'update_address']);

    // Cart routes
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/add-to-cart', [CartController::class, 'add_cart']);
    Route::post('/remove-from-cart', [CartController::class, 'remove_cart']);
    Route::get('/checkout', [CartController::class, 'checkout']);
    Route::post('/update-cart-amount', [CartController::class, 'update_cart_by_collection_amount']);

    // Order routes
    Route::post('/order', [OrderController::class, 'index']);
    Route::get('/order-list', [OrderController::class, 'get_order_list']);
    Route::get('/order-details', [OrderController::class, 'order_details']);

    // PayLater
    Route::get('/PayLater', [PayLaterController::class, 'index']);
});

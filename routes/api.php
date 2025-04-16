<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoriesController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\api\OrderController;
use App\Http\Controllers\Api\ProfileController;
use GuzzleHttp\Psr7\Request;

// Public Routes (No Authentication Required)
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/app_version', [AuthController::class, 'app_version']);
Route::get('/pincode_access', [AuthController::class, 'pincode_access']);

// Routes with Sanctum Middleware
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/home', [HomeController::class, 'index']);
    Route::get('/profile', [ProfileController::class, 'index']);
    Route::post('/update_profile', [ProfileController::class, 'update_profile']);
    Route::post('/update_address', [ProfileController::class, 'update_address']);
    Route::get('/categories', [CategoriesController::class, 'index']);
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/product-details', [ProductController::class, 'details']);
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/add-to-cart', [CartController::class, 'add_cart']);
    Route::post('/remove-from-cart', [CartController::class, 'remove_cart']);
    Route::post('/order', [OrderController::class, 'index']);
    Route::get('/order-list', [OrderController::class, 'get_order_list']);

});

 

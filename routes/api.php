<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoriesController;
use GuzzleHttp\Psr7\Request;

// Public Routes (No Authentication Required)
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/app_version', [AuthController::class, 'app_version']);
Route::get('/pincode_access', [AuthController::class, 'pincode_access']);

// Routes with Sanctum Middleware
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/categories', [CategoriesController::class, 'index']);
    Route::get('/user', [AuthController::class, 'user']);
});

<<<<<<< HEAD
 
=======
 
>>>>>>> rabil

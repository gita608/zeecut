<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoriesConroller;
use App\Http\Controllers\ProductContoller;
use App\Http\Controllers\UserController;
use App\Http\Middleware\RoleMiddleware;

Route::get('/', [AuthController::class, 'login'])->name('login');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/verify', [AuthController::class, 'verify'])->name('verify');
Route::get('/register', [AuthController::class, 'register'])->name('register');


Route::middleware(RoleMiddleware::class)->group(function () {
    //dashboard
    Route::get('/admin-dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');


    //User
    Route::get('/user', [UserController::class, 'index'])->name('user.index');
    Route::get('/user-add', [UserController::class, 'ajax_add'])->name('user.add');
    Route::post('/user-submit', [UserController::class, 'submit'])->name('user.submit');
    Route::get('/user-edit/{id}', [UserController::class, 'ajax_edit'])->name('user.edit');
    Route::post('/user-update/{id}', [UserController::class, 'update'])->name('user.update');
    Route::get('/user-delete/{id}', [UserController::class, 'delete'])->name('user.delete');

    //product
    Route::get('/product', [ProductContoller::class, 'index'])->name('product.index');
    Route::get('/product-add', [ProductContoller::class, 'ajax_add'])->name('product.add');
    Route::post('/product-submit', [ProductContoller::class, 'submit'])->name('product.submit');
    Route::get('/product-edit/{id}', [ProductContoller::class, 'ajax_edit'])->name('product.edit');
    Route::post('/product-update/{id}', [ProductContoller::class, 'update'])->name('product.update');
    Route::get('/product-delete/{id}', [ProductContoller::class, 'delete'])->name('product.delete');



});


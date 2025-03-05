<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoriesConroller;
use App\Http\Controllers\UserController;
use App\Http\Middleware\RoleMiddleware;

Route::get('/', [AuthController::class, 'login'])->name('login');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::post('/verify', [AuthController::class , 'verify'])->name('verify');
Route::get('/register', [AuthController::class , 'register'])->name('register');


Route::middleware(RoleMiddleware::class)->group(function () {
    //dashboard
    Route::get('/admin-dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    Route::get('/user', [UserController::class, 'index'])->name('user.index');



    //category
    Route::get('/categories', [CategoriesConroller::class, 'index'])->name('categories.index');
    Route::get('/categories-add', [CategoriesConroller::class, 'add'])->name('categories.add');
    Route::get('/categories-edit/{id}', [CategoriesConroller::class, 'edit'])->name('categories.edit');
    Route::post('/categories-submit', [CategoriesConroller::class, 'submit'])->name('categories.submit');
    Route::post('/categories-update/{id}', [CategoriesConroller::class, 'update'])->name('categories.update');
    Route::get('/categories-delete/{id}', [CategoriesConroller::class, 'delete'])->name('categories.delete');

});


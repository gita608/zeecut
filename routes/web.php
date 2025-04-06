<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BannerContoller;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\ProductContoller;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\PincodeAccesController;

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

    // Category Routes
    Route::get('/category', [CategoryController::class, 'index'])->name('category.index');
    Route::get('/category-add', [CategoryController::class, 'ajax_add'])->name('category.add');
    Route::get('/category-edit/{id}', [CategoryController::class, 'ajax_edit'])->name('category.edit');
    Route::post('/category-submit', [CategoryController::class, 'submit'])->name('category.submit');
    Route::put('/category-update/{id}', [CategoryController::class, 'update'])->name('category.update');
    Route::get('/category-delete/{id}', [CategoryController::class, 'delete'])->name('category.delete');

    // Product
    Route::get('/product', [ProductContoller::class, 'index'])->name('product.index');
    Route::get('/product-add', [ProductContoller::class, 'ajax_add'])->name('product.add');
    Route::post('/product-submit', [ProductContoller::class, 'submit'])->name('product.submit');
    Route::get('/product-edit/{id}', [ProductContoller::class, 'ajax_edit'])->name('product.edit');
    Route::get('/product-view_images/{id}', [ProductContoller::class, 'view_images'])->name('product.view_images');
    Route::post('/product-update/{id}', [ProductContoller::class, 'update'])->name('product.update');
    Route::get('/product-delete/{id}', [ProductContoller::class, 'delete'])->name('product.delete');
    Route::post('/product/toggle-status', [ProductContoller::class, 'toggleStatus'])->name('product.toggleStatus');
    Route::get('/product/get_has_collection', [ProductContoller::class, 'get_has_collection'])->name('product.get_has_collection');
    Route::post('/product/upload_image', [ProductContoller::class, 'upload_image'])->name('product.upload_image');
    Route::delete('/product/delete_image/{id}', [ProductContoller::class, 'delete_image'])->name('product.delete_image');

    // Offers
    Route::get('/offer', [OfferController::class, 'index'])->name('offer.index');
    Route::get('/offer-add', [OfferController::class, 'ajax_add'])->name('offer.add');
    Route::get('/offer-edit/{id}', [OfferController::class, 'ajax_edit'])->name(name: 'offer.edit');
    Route::post('/offer-submit', [OfferController::class, 'submit'])->name('offer.submit');
    Route::put('/offer-update/{id}', [OfferController::class, 'update'])->name('offer.update');
    Route::get('/offer-delete/{id}', [OfferController::class, 'delete'])->name('offer.delete');

    // Pincode Routes
    Route::get('/pincode', [PincodeAccesController::class, 'index'])->name('pincode.index');
    Route::get('/pincode-add', [PincodeAccesController::class, 'ajax_add'])->name('pincode.add');
    Route::get('/pincode-edit/{id}', [PincodeAccesController::class, 'ajax_edit'])->name('pincode.edit');
    Route::post('/pincode-submit', [PincodeAccesController::class, 'submit'])->name('pincode.submit');
    Route::put('/pincode-update/{id}', [PincodeAccesController::class, 'update'])->name('pincode.update');
    Route::get('/pincode-delete/{id}', [PincodeAccesController::class, 'delete'])->name('pincode.delete');

    // Banner Routes
    Route::get('/banner', [BannerContoller::class, 'index'])->name('banner.index');
    Route::get('/banner-add', [BannerContoller::class, 'add'])->name('banner.add');
    Route::get('/banner-edit/{id}', [BannerContoller::class, 'ajax_edit'])->name('banner.edit');
    Route::post('/banner-submit', [BannerContoller::class, 'submit'])->name('banner.submit');
    Route::put('/banner-update/{id}', [BannerContoller::class, 'update'])->name('banner.update');
    Route::get('/banner-delete/{id}', [BannerContoller::class, 'delete'])->name('banner.delete');


});


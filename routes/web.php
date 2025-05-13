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
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PayLaterController;

Route::get('/', [AuthController::class, 'login'])->name('login');
Route::post('/verify', [AuthController::class, 'verify'])->name('verify');
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::get('/privacy', [HomeController::class, 'privacy'])->name('privacy');
Route::get('/delete-account', [HomeController::class, 'delete_account'])->name('delete-account');


Route::middleware(RoleMiddleware::class)->group(function () {
    //dashboard
    Route::get('/admin-dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

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
    
    //stock
    Route::get('/stock',[StockController::class,'index'])->name('stock.index');
    Route::post('/update-quantity', [StockController::class, 'update_quantity'])->name('stocks.update_quantity');
    
    //settings
    Route::get('/setting',[SettingController::class,'index'])->name('setting.index');
    Route::post('/setting-update',[SettingController::class,'update'])->name('setting.update');
    
    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders');
    Route::post('/updateStatus/{id}', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::get('/details/{id}', [OrderController::class, 'details'])->name('orders.details');
    Route::get('/send_notify', [OrderController::class, 'send']);

    //payl_later
    Route::get('pay-later',[PayLaterController::class,'index'])->name('payLater.index');
    Route::get('payLater-add',[PayLaterController::class,'ajax_add'])->name('payLater.add');
    Route::post('submit',[PayLaterController::class,'submit'])->name('payLater.submit');
    Route::get('payLater-edit/{id}',[PayLaterController::class,'ajax_edit'])->name('payLater.edit');
    Route::get('payLater-delete/{id}',[PayLaterController::class,'delete'])->name('payLater.delete');
    Route::post('update/{data}',action: [PayLaterController::class,'update'])->name('payLater.update');
    Route::post('/payLater/toggle-status', [PayLaterController::class, 'toggleStatus'])->name('payLater.toggleStatus');


});

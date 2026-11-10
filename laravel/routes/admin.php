<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\InquiryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductImageController;
use App\Http\Controllers\Admin\ProductTypeController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SettingController;
use Illuminate\Support\Facades\Route;

/*
| Admin panel — /admin. Loaded with the "web" middleware group (sessions, CSRF).
| Every route except login requires a signed-in admin (auth + admin middleware).
*/

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [AuthController::class, 'create'])->name('login');
        Route::post('login', [AuthController::class, 'store'])->middleware('throttle:10,1')->name('login.store');
    });

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::post('logout', [AuthController::class, 'destroy'])->name('logout');

        Route::get('/', DashboardController::class)->name('dashboard');

        Route::resource('products', ProductController::class)->except('show');

        Route::get('product-images', [ProductImageController::class, 'index'])->name('product-images.index');
        Route::get('products/{product}/images', [ProductImageController::class, 'show'])->name('product-images.show');
        Route::post('products/{product}/images', [ProductImageController::class, 'store'])->name('product-images.store');
        Route::put('product-images/{image}', [ProductImageController::class, 'update'])->name('product-images.update');
        Route::post('product-images/{image}/feature', [ProductImageController::class, 'feature'])->name('product-images.feature');
        Route::delete('product-images/{image}', [ProductImageController::class, 'destroy'])->name('product-images.destroy');

        Route::resource('categories', CategoryController::class)->except('show');
        Route::resource('categories.types', ProductTypeController::class)->except(['index', 'show'])->shallow();

        Route::resource('gallery', GalleryController::class)->except('show')->parameters(['gallery' => 'item']);

        Route::get('inquiries', [InquiryController::class, 'index'])->name('inquiries.index');
        Route::get('inquiries/{inquiry}', [InquiryController::class, 'show'])->name('inquiries.show');
        Route::put('inquiries/{inquiry}', [InquiryController::class, 'update'])->name('inquiries.update');
        Route::delete('inquiries/{inquiry}', [InquiryController::class, 'destroy'])->name('inquiries.destroy');

        Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
        Route::put('settings', [SettingController::class, 'update'])->name('settings.update');

        Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('profile/password', [ProfileController::class, 'password'])->name('profile.password');
    });
});

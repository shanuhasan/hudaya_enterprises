<?php

use App\Http\Controllers\Site\ContactController;
use App\Http\Controllers\Site\GalleryController;
use App\Http\Controllers\Site\HomeController;
use App\Http\Controllers\Site\PageController;
use App\Http\Controllers\Site\ProductController;
use Illuminate\Support\Facades\Route;

/*
| Public website. Route names are used everywhere in Blade (route('about') …),
| so URLs can change here without touching the views.
*/

Route::get('/', HomeController::class)->name('home');

Route::controller(PageController::class)->group(function () {
    Route::get('/about', 'about')->name('about');
    Route::get('/manufacturing', 'manufacturing')->name('manufacturing');
    Route::get('/quality', 'quality')->name('quality');
});

Route::controller(ProductController::class)->prefix('products')->name('products.')->group(function () {
    Route::get('/', 'index')->name('index');
    // Module 4: {category:slug} bound to the Category model.
    Route::get('/{category}', 'category')->whereIn('category', ['lowers', 'jackets'])->name('category');
});

Route::get('/gallery', GalleryController::class)->name('gallery');
Route::get('/contact', ContactController::class)->name('contact');

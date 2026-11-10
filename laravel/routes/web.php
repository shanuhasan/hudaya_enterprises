<?php

use App\Http\Controllers\Site\ContactController;
use App\Http\Controllers\Site\GalleryController;
use App\Http\Controllers\Site\HomeController;
use App\Http\Controllers\Site\InquiryController;
use App\Http\Controllers\Site\PageController;
use App\Http\Controllers\Site\ProductController;
use App\Http\Controllers\Site\SeoController;
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
    Route::get('/{category:slug}', 'category')->name('category'); // active categories only (Category::resolveRouteBinding)
});

Route::get('/gallery', GalleryController::class)->name('gallery');
Route::get('/contact', ContactController::class)->name('contact');
Route::post('/contact', [InquiryController::class, 'store'])->middleware('throttle:inquiries')->name('inquiries.store');

Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [SeoController::class, 'robots'])->name('robots');

// Phase 1 static URLs → permanent redirects, so old links and search results keep working.
foreach ([
    'index' => '/', 'about' => '/about', 'products' => '/products', 'lowers' => '/products/lowers',
    'jackets' => '/products/jackets', 'manufacturing' => '/manufacturing', 'quality' => '/quality',
    'gallery' => '/gallery', 'contact' => '/contact',
] as $old => $new) {
    Route::permanentRedirect("/$old.html", $new);
}

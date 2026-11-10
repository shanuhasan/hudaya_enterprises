<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\GalleryItem;
use App\Models\Page;
use App\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        return view('pages.home', [
            'page' => Page::findByKey('home'),
            'categories' => Category::query()->active()->ordered()->get(),
            'featured' => Product::query()->active()->featured()
                ->whereHas('category', fn ($q) => $q->active())
                ->with('category')->limit(8)->get(),
            'mosaic' => GalleryItem::query()->active()->onHome()->limit(5)->get(),
        ]);
    }
}

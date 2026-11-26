<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Page;
use App\Models\Product;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        return view('pages.products', [
            'page' => Page::findByKey('products'),
            'categories' => Category::query()->active()->ordered()
                ->with(['types' => fn ($q) => $q->active()])->get(),
            'featured' => Product::query()->active()->featured()
                ->whereHas('category', fn ($q) => $q->active())
                ->with('category')->limit(4)->get(),
        ]);
    }

    /** /products/{category:slug} — inactive categories 404 (see Category::resolveRouteBinding). */
    public function category(Category $category): View
    {
        $category->load([
            'types' => fn ($q) => $q->active(),
            'products' => fn ($q) => $q->active()->with(['category', 'types' => fn ($t) => $t->active()]),
        ]);

        return view('pages.category', compact('category'));
    }
}

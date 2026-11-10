<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Page;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    public function sitemap(): Response
    {
        $pages = Page::query()->get()->keyBy('key');
        $urls = collect(['home', 'about', 'products', 'manufacturing', 'quality', 'gallery', 'contact'])
            ->map(fn ($key) => ['loc' => route($key === 'products' ? 'products.index' : $key), 'lastmod' => $pages[$key]->updated_at ?? null]);

        Category::query()->active()->ordered()->get()->each(function (Category $category) use ($urls) {
            $urls->push(['loc' => route('products.category', $category), 'lastmod' => $category->updated_at]);
        });

        return response()->view('seo.sitemap', ['urls' => $urls], 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }

    public function robots(): Response
    {
        $lines = app()->isProduction()
            ? ['User-agent: *', 'Disallow: /admin', '', 'Sitemap: '.route('sitemap')]
            : ['User-agent: *', 'Disallow: /']; // never index staging / local copies

        return response(implode("\n", $lines)."\n", 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }
}

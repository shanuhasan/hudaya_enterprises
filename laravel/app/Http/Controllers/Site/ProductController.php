<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        return view('pages.products');
    }

    /** Module 4 replaces the slug string with the Category model (route-model binding). */
    public function category(string $category): View
    {
        return view('pages.'.$category);
    }
}

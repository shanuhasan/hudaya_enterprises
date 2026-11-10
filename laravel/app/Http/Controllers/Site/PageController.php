<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\View\View;

/** Content pages: About, Manufacturing, Quality (hero + SEO from the pages table). */
class PageController extends Controller
{
    public function about(): View
    {
        return view('pages.about', ['page' => Page::findByKey('about')]);
    }

    public function manufacturing(): View
    {
        return view('pages.manufacturing', ['page' => Page::findByKey('manufacturing')]);
    }

    public function quality(): View
    {
        return view('pages.quality', ['page' => Page::findByKey('quality')]);
    }
}

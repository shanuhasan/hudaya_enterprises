<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

/** Content pages: About, Manufacturing, Quality. */
class PageController extends Controller
{
    public function about(): View
    {
        return view('pages.about');
    }

    public function manufacturing(): View
    {
        return view('pages.manufacturing');
    }

    public function quality(): View
    {
        return view('pages.quality');
    }
}

<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function __invoke(): View
    {
        return view('pages.gallery');
    }
}

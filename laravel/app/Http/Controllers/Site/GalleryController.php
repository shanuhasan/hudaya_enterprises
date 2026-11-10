<?php

namespace App\Http\Controllers\Site;

use App\Enums\GalleryGroup;
use App\Http\Controllers\Controller;
use App\Models\GalleryItem;
use App\Models\Page;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function __invoke(): View
    {
        return view('pages.gallery', [
            'page' => Page::findByKey('gallery'),
            'items' => GalleryItem::query()->active()->ordered()->get(),
            'groups' => GalleryGroup::cases(),
        ]);
    }
}

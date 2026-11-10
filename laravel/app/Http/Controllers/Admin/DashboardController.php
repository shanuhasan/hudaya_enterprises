<?php

namespace App\Http\Controllers\Admin;

use App\Enums\InquiryStatus;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\GalleryItem;
use App\Models\Inquiry;
use App\Models\Product;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'stats' => [
                'products' => Product::query()->count(),
                'activeProducts' => Product::query()->active()->count(),
                'featuredProducts' => Product::query()->whereNotNull('featured_position')->count(),
                'categories' => Category::query()->count(),
                'activeCategories' => Category::query()->active()->count(),
                'inquiries' => Inquiry::query()->count(),
                'inquiriesThisMonth' => Inquiry::query()->where('created_at', '>=', now()->startOfMonth())->count(),
                'pending' => Inquiry::query()->unread()->count(),
                'gallery' => GalleryItem::query()->count(),
            ],
            'statusCounts' => Inquiry::query()->selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status'),
            'recent' => Inquiry::query()->latest()->limit(8)->get(),
            'statuses' => InquiryStatus::cases(),
        ]);
    }
}

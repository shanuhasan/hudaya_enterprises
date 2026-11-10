<?php

namespace App\Http\Controllers\Site;

use App\Enums\BuyerType;
use App\Enums\InquiryCategory;
use App\Enums\Timeline;
use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use App\Models\Page;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function __invoke(): View
    {
        return view('pages.contact', [
            'page' => Page::findByKey('contact'),
            'buyerTypes' => BuyerType::options(),
            'inquiryCategories' => InquiryCategory::options(),
            'timelines' => Timeline::options(),
            'customisationOptions' => Inquiry::CUSTOMISATION_OPTIONS,
        ]);
    }
}

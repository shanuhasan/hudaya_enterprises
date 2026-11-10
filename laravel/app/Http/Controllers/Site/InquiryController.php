<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInquiryRequest;
use App\Mail\InquiryReceived;
use App\Models\Inquiry;
use App\Services\SettingsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Throwable;

class InquiryController extends Controller
{
    public function store(StoreInquiryRequest $request, SettingsService $settings): RedirectResponse
    {
        $thanks = redirect()->to(route('contact').'#quote')->with('inquiry_sent', true);

        // Honeypot filled → a bot. Pretend success, store nothing.
        if (filled($request->input('website'))) {
            return $thanks;
        }

        $inquiry = Inquiry::query()->create($request->safe()->except(['consent', 'website']) + [
            'source_url' => Str::limit((string) url()->previous(), 490, ''),
            'ip_address' => $request->ip(),
            'user_agent' => Str::limit((string) $request->userAgent(), 490, ''),
        ]);

        // Email the company when an address is set. A mail failure must never lose the inquiry:
        // it is already saved and visible in Admin → Inquiries.
        if ($to = $settings->get('email')) {
            try {
                Mail::to($to)->send(new InquiryReceived($inquiry));
            } catch (Throwable $e) {
                Log::warning('Inquiry notification email failed', ['inquiry' => $inquiry->id, 'error' => $e->getMessage()]);
            }
        }

        return $thanks;
    }
}

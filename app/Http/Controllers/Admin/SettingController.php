<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\ImageService;
use App\Services\SettingsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/** Company details shown across the website (contact lines, WhatsApp buttons, footer, logo…). */
class SettingController extends Controller
{
    private const RULES = [
        'name' => ['required', 'string', 'max:120'],
        'tagline' => ['nullable', 'string', 'max:200'],
        'tagline_alt' => ['nullable', 'string', 'max:200'],
        'intro' => ['nullable', 'string', 'max:500'],
        'phone' => ['nullable', 'string', 'max:40'],
        'phone_href' => ['nullable', 'regex:/^\+?[0-9]{7,15}$/'],
        'email' => ['nullable', 'email', 'max:190'],
        'whatsapp_number' => ['nullable', 'digits_between:8,15'],
        'whatsapp_message' => ['nullable', 'string', 'max:300'],
        'address' => ['nullable', 'string', 'max:500'],
        'hours' => ['nullable', 'string', 'max:120'],
        'instagram' => ['nullable', 'url:https', 'max:255'],
        'facebook' => ['nullable', 'url:https', 'max:255'],
        'linkedin' => ['nullable', 'url:https', 'max:255'],
        'announcement' => ['nullable', 'string', 'max:160'],
        'announcement_short' => ['nullable', 'string', 'max:80'],
        'certifications' => ['nullable', 'string', 'max:1000'],
        'photo_notice' => ['nullable', 'string', 'max:300'],
    ];

    public function __construct(private readonly ImageService $images) {}

    public function edit(): View
    {
        return view('admin.settings.edit', [
            'settings' => Setting::query()->orderBy('sort_order')->get()->keyBy('key'),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate(self::RULES + [
            'logo_file' => ['nullable', 'image', 'mimes:png,webp,jpg,jpeg', 'max:2048', 'dimensions:min_width=200,max_width=6000'],
            'logo_on_dark_file' => ['nullable', 'image', 'mimes:png,webp,jpg,jpeg', 'max:2048', 'dimensions:min_width=200,max_width=6000'],
            'remove_logo' => ['boolean'],
            'remove_logo_on_dark' => ['boolean'],
        ], [
            'phone_href.regex' => 'Use digits only, optionally starting with +, e.g. +919800000000.',
            'whatsapp_number.digits_between' => 'Use digits only with the country code, e.g. 919800000000.',
            'url' => 'Enter a full https:// address.',
        ], [
            'phone_href' => 'phone for dialling',
            'whatsapp_number' => 'WhatsApp number',
            'logo_file' => 'logo',
            'logo_on_dark_file' => 'logo for dark backgrounds',
        ]);

        $current = Setting::query()->pluck('value', 'key');

        // Two logo versions: setting key => [upload field, remove checkbox]
        foreach (['logo' => ['logo_file', 'remove_logo'], 'logo_on_dark' => ['logo_on_dark_file', 'remove_logo_on_dark']] as $key => [$file, $remove]) {
            if ($request->hasFile($file)) {
                $this->images->delete($current[$key] ?? null);
                $data[$key] = $this->images->storeLogo($request->file($file));
            } elseif ($request->boolean($remove)) {
                $this->images->delete($current[$key] ?? null);
                $data[$key] = '';
            }
        }

        foreach ([...array_keys(self::RULES), 'logo', 'logo_on_dark'] as $key) {
            if (! array_key_exists($key, $data)) {
                continue;
            }
            $value = (string) ($data[$key] ?? '');
            if (($current[$key] ?? null) !== $value) {
                Setting::query()->where('key', $key)->update(['value' => $value, 'updated_at' => now()]);
            }
        }

        SettingsService::flush();

        return redirect()->route('admin.settings.edit')->with('status', 'Company settings saved. The website shows the new details now.');
    }
}

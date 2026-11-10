<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

/**
 * Company details. Values come from config/site.php (empty = not set yet).
 * Re-running only adds missing keys — values already edited in the admin are kept.
 */
class SettingSeeder extends Seeder
{
    public function run(): void
    {
        // key => [group, type, label, help]
        $definitions = [
            'name' => ['company', 'text', 'Company name', null],
            'logo' => ['company', 'image', 'Logo (light version for dark backgrounds)', 'PNG with a transparent background. Empty = the supplied Hudaya logo.'],
            'tagline' => ['company', 'text', 'Tagline', null],
            'tagline_alt' => ['company', 'text', 'Secondary tagline', null],
            'intro' => ['company', 'textarea', 'Short introduction', 'Footer text and structured data description.'],
            'announcement' => ['content', 'text', 'Announcement bar (desktop)', 'Leave empty to hide the announcement bar.'],
            'announcement_short' => ['content', 'text', 'Announcement bar (mobile)', null],
            'phone' => ['contact', 'tel', 'Phone (as displayed)', 'e.g. +91 98000 00000'],
            'phone_href' => ['contact', 'tel', 'Phone (for dialling)', 'International format without spaces, e.g. +919800000000. Empty = number shown as text only.'],
            'email' => ['contact', 'email', 'Email', 'Also receives inquiry notifications.'],
            'whatsapp_number' => ['contact', 'tel', 'WhatsApp number', 'Digits only with country code, e.g. 919800000000. Empty = all WhatsApp buttons hidden.'],
            'whatsapp_message' => ['contact', 'text', 'WhatsApp opening message', null],
            'address' => ['contact', 'textarea', 'Address', null],
            'hours' => ['contact', 'text', 'Business hours', null],
            'instagram' => ['social', 'url', 'Instagram URL', 'Empty = icon hidden.'],
            'facebook' => ['social', 'url', 'Facebook URL', 'Empty = icon hidden.'],
            'linkedin' => ['social', 'url', 'LinkedIn URL', 'Empty = icon hidden.'],
            'certifications' => ['content', 'textarea', 'Certifications', 'Only verified certifications. Empty = section hidden on the Quality page.'],
            'photo_notice' => ['content', 'text', 'Stock photo notice', 'Shown in the footer and gallery. Clear it once all photos are your own.'],
        ];

        $order = 0;
        foreach ($definitions as $key => [$group, $type, $label, $help]) {
            Setting::query()->firstOrCreate(
                ['key' => $key],
                [
                    'value' => config("site.$key", ''),
                    'group' => $group,
                    'type' => $type,
                    'label' => $label,
                    'help' => $help,
                    'sort_order' => ++$order,
                ],
            );
        }
    }
}

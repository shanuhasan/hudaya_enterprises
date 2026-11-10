<?php

namespace App\View\Composers;

use Illuminate\Support\Fluent;
use Illuminate\View\View;

/**
 * Shares the company details with every view as $site.
 * $site->phone, $site->whatsapp_number … (missing keys return null).
 * The values are built once per request (container singleton "site", see AppServiceProvider).
 */
class SiteComposer
{
    public function compose(View $view): void
    {
        $view->with('site', app('site'));
    }

    /** Builds $site and adds derived values so views don't rebuild them. */
    public static function build(array $values): Fluent
    {
        $number = $values['whatsapp_number'] ?? '';

        $values['whatsapp_profile_url'] = $number ? 'https://wa.me/'.$number : null;
        $values['whatsapp_url'] = $number
            ? 'https://wa.me/'.$number.'?text='.rawurlencode($values['whatsapp_message'] ?? '')
            : null;

        return new Fluent($values);
    }
}

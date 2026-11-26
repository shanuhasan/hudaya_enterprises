<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Light theme (logo colours): the site now needs two logo versions.
 *   logo          → for light backgrounds (original colours)   — relabelled
 *   logo_on_dark  → for dark backgrounds (white lettering)      — new
 * Any value already uploaded is kept.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::table('settings')->where('key', 'logo')->update([
            'label' => 'Logo (for light backgrounds)',
            'help' => 'Original colours, PNG with a transparent background. Empty = the supplied Hudaya logo.',
        ]);

        if (! DB::table('settings')->where('key', 'logo_on_dark')->exists()) {
            $order = (int) DB::table('settings')->where('key', 'logo')->value('sort_order');

            DB::table('settings')->insert([
                'key' => 'logo_on_dark',
                'value' => '',
                'group' => 'company',
                'type' => 'image',
                'label' => 'Logo (for dark backgrounds)',
                'help' => 'White lettering, PNG with a transparent background. Empty = the supplied Hudaya logo.',
                'sort_order' => $order,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        Cache::forget('settings.all');
    }

    public function down(): void
    {
        DB::table('settings')->where('key', 'logo_on_dark')->delete();
        DB::table('settings')->where('key', 'logo')->update([
            'label' => 'Logo (light version for dark backgrounds)',
            'help' => 'PNG with a transparent background. Empty = the supplied Hudaya logo.',
        ]);
        Cache::forget('settings.all');
    }
};

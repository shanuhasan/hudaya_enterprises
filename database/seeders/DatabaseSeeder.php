<?php

namespace Database\Seeders;

use App\Services\SettingsService;
use Illuminate\Database\Seeder;

/**
 * Loads the approved Phase 1 content. Safe to re-run: existing rows are kept,
 * so edits made later in the admin are never overwritten.
 * Admin users are created separately in Phase 3 (no default account with a known password).
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            SettingSeeder::class,
            PageSeeder::class,
            CatalogueSeeder::class,
            GallerySeeder::class,
        ]);

        SettingsService::flush();
    }
}

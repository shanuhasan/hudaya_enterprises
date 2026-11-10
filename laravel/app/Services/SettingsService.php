<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Throwable;

/**
 * Company details from the `settings` table, cached until a setting changes.
 * Falls back to config/site.php (the seed values) when the table is not there yet,
 * e.g. before `php artisan migrate` on a fresh checkout.
 */
class SettingsService
{
    private const CACHE_KEY = 'settings.all';

    /** @return array<string, string> */
    public function all(): array
    {
        try {
            return Cache::rememberForever(self::CACHE_KEY, function () {
                if (! Schema::hasTable('settings')) {
                    throw new \RuntimeException('settings table missing');
                }

                return Setting::query()->pluck('value', 'key')->map(fn ($v) => (string) $v)->all();
            });
        } catch (Throwable) {
            return array_map('strval', config('site'));
        }
    }

    public function get(string $key, string $default = ''): string
    {
        return $this->all()[$key] ?? $default;
    }

    public static function flush(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}

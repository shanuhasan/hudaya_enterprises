<?php

namespace App\Models;

use App\Services\SettingsService;
use Illuminate\Database\Eloquent\Model;

/**
 * One company detail / site-wide value (key → value). Read through SettingsService,
 * which caches all rows; saving or deleting a row clears that cache.
 */
class Setting extends Model
{
    protected $fillable = ['key', 'value', 'group', 'type', 'label', 'help', 'sort_order'];

    protected static function booted(): void
    {
        static::saved(fn () => SettingsService::flush());
        static::deleted(fn () => SettingsService::flush());
    }
}

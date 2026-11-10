<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** SEO and hero content of one fixed page; `key` matches the route name (home, about…). */
class Page extends Model
{
    protected $fillable = [
        'key', 'meta_title', 'meta_description',
        'hero_eyebrow', 'hero_title', 'hero_title_em', 'hero_title_line2', 'hero_lead', 'hero_image', 'hero_image_alt',
    ];

    public static function findByKey(string $key): self
    {
        return static::query()->where('key', $key)->firstOrFail();
    }
}

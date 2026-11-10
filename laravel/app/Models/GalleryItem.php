<?php

namespace App\Models;

use App\Enums\GalleryGroup;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class GalleryItem extends Model
{
    /** Allowed image proportions (width/height). */
    public const ASPECTS = ['4/5', '3/4', '4/3', '1/1'];

    protected $fillable = [
        'caption', 'short_caption', 'group', 'image', 'image_alt', 'aspect', 'home_position', 'sort_order', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'group' => GalleryGroup::class,
            'home_position' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): void
    {
        $query->orderBy('sort_order')->orderBy('id');
    }

    /** Images chosen for the Home mosaic, in slot order. */
    public function scopeOnHome(Builder $query): void
    {
        $query->whereNotNull('home_position')->orderBy('home_position');
    }
}

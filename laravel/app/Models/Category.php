<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = [
        'name', 'slug', 'title', 'title_em', 'summary', 'highlights', 'card_image', 'card_image_alt',
        'hero_eyebrow', 'hero_title', 'hero_title_em', 'hero_lead', 'hero_image',
        'intro_title', 'intro_title_em', 'intro_text', 'intro_image', 'intro_image_alt', 'intro_image_position',
        'types_title', 'types_title_em', 'specs',
        'cta_eyebrow', 'cta_title', 'cta_title_em', 'cta_text',
        'meta_title', 'meta_description', 'sort_order', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'highlights' => 'array',
            'specs' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function types(): HasMany
    {
        return $this->hasMany(ProductType::class)->orderBy('sort_order');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class)->orderBy('sort_order');
    }

    /**
     * The public route binds {category:slug} and must only find active categories (inactive → 404).
     * The admin binds by id and sees every category.
     */
    public function resolveRouteBinding($value, $field = null): ?self
    {
        if ($field === 'slug') {
            return $this->newQuery()->active()->where('slug', $value)->firstOrFail();
        }

        return parent::resolveRouteBinding($value, $field);
    }

    /** Position label used on tiles and showcases: "01", "02"… */
    public function indexLabel(): string
    {
        return sprintf('%02d', $this->sort_order);
    }

    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): void
    {
        $query->orderBy('sort_order')->orderBy('id');
    }
}

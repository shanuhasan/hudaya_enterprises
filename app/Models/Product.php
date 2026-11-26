<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id', 'name', 'slug', 'type_label', 'description', 'material', 'sizes', 'moq', 'image', 'image_alt',
        'featured_position', 'sort_order', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'featured_position' => 'integer',
            'sizes' => 'array',
        ];
    }

    protected static function booted(): void
    {
        // Slug from the name when none is given ("Men's Slim Track Pant" → "mens-slim-track-pant").
        static::saving(function (Product $product) {
            if (blank($product->slug)) {
                $product->slug = Str::slug(str_replace("'", '', $product->name));
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function types(): BelongsToMany
    {
        return $this->belongsToMany(ProductType::class)->orderBy('sort_order');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order')->orderBy('id');
    }

    public function isFeatured(): bool
    {
        return $this->featured_position !== null;
    }

    /** Space-separated type slugs for the filter grid (data-category), e.g. "mens sports". */
    public function filterKeys(): string
    {
        return $this->types->pluck('slug')->implode(' ');
    }

    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    /** Featured styles, in their featured order. */
    public function scopeFeatured(Builder $query): void
    {
        $query->whereNotNull('featured_position')->orderBy('featured_position');
    }
}

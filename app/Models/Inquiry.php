<?php

namespace App\Models;

use App\Enums\BuyerType;
use App\Enums\InquiryCategory;
use App\Enums\InquiryStatus;
use App\Enums\Timeline;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    /** Checkbox options on the quote form ("Customisation needs"). */
    public const CUSTOMISATION_OPTIONS = [
        'Private label / branding',
        'Custom fabric',
        'Custom colours',
        'Custom sizing / fit',
        'Custom trims',
        'Packing requirements',
    ];

    protected $fillable = [
        'name', 'company', 'email', 'phone', 'country', 'city', 'buyer_type', 'category', 'product',
        'quantity', 'timeline', 'customisation', 'message',
        'status', 'admin_notes', 'read_at', 'source_url', 'ip_address', 'user_agent',
    ];

    protected $attributes = [
        'status' => 'new',
    ];

    protected function casts(): array
    {
        return [
            'buyer_type' => BuyerType::class,
            'category' => InquiryCategory::class,
            'timeline' => Timeline::class,
            'status' => InquiryStatus::class,
            'customisation' => 'array',
            'read_at' => 'datetime',
        ];
    }

    public function scopeUnread(Builder $query): void
    {
        $query->where('status', InquiryStatus::New);
    }

    public function markAsRead(): void
    {
        if ($this->status === InquiryStatus::New) {
            $this->forceFill(['status' => InquiryStatus::Read, 'read_at' => now()])->save();
        }
    }
}

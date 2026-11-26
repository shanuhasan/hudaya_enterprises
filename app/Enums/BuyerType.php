<?php

namespace App\Enums;

/** "Business type" select on the quote form. */
enum BuyerType: string
{
    use HasOptions;

    case Brand = 'brand';
    case PrivateLabel = 'private-label';
    case Wholesaler = 'wholesaler';
    case Retailer = 'retailer';
    case FashionBusiness = 'fashion-business';
    case BulkBuyer = 'bulk-buyer';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Brand => 'Clothing brand',
            self::PrivateLabel => 'Private label brand',
            self::Wholesaler => 'Wholesaler',
            self::Retailer => 'Retailer',
            self::FashionBusiness => 'Fashion business',
            self::BulkBuyer => 'Bulk buyer',
            self::Other => 'Other',
        };
    }
}

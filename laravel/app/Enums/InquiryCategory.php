<?php

namespace App\Enums;

/** "Product category" select on the quote form. */
enum InquiryCategory: string
{
    use HasOptions;

    case Lowers = 'lowers';
    case Jackets = 'jackets';
    case Both = 'both';
    case Custom = 'custom';

    public function label(): string
    {
        return match ($this) {
            self::Lowers => 'Lowers & track pants',
            self::Jackets => 'Jackets',
            self::Both => 'Both',
            self::Custom => 'Custom / not sure yet',
        };
    }
}

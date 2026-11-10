<?php

namespace App\Enums;

/** Gallery filter tabs, in display order. */
enum GalleryGroup: string
{
    use HasOptions;

    case Lowers = 'lowers';
    case Jackets = 'jackets';
    case Manufacturing = 'manufacturing';
    case Details = 'details';

    public function label(): string
    {
        return match ($this) {
            self::Lowers => 'Lowers',
            self::Jackets => 'Jackets',
            self::Manufacturing => 'Manufacturing',
            self::Details => 'Details',
        };
    }
}

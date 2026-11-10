<?php

namespace App\Enums;

/** "Target timeline" select on the quote form (optional field). */
enum Timeline: string
{
    use HasOptions;

    case Asap = 'asap';
    case OneMonth = '1-month';
    case OneToThreeMonths = '1-3-months';
    case ThreePlusMonths = '3-plus-months';

    public function label(): string
    {
        return match ($this) {
            self::Asap => 'As soon as possible',
            self::OneMonth => 'Within 1 month',
            self::OneToThreeMonths => '1–3 months',
            self::ThreePlusMonths => 'More than 3 months',
        };
    }
}

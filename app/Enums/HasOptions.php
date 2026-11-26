<?php

namespace App\Enums;

/** Shared helpers for string-backed enums with a label() method. */
trait HasOptions
{
    /** @return array<string, string> value => label (for selects and validation) */
    public static function options(): array
    {
        return array_combine(
            array_column(self::cases(), 'value'),
            array_map(fn (self $case) => $case->label(), self::cases()),
        );
    }

    /** @return list<string> */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}

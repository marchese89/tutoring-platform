<?php

namespace App\Helpers;

class NumberHelper
{
    public static function format(int|float $value, int $precision = 2, ?string $locale = null): string
    {
        $locale ??= app()->getLocale();
        $usesCommaDecimal = str_starts_with(strtolower($locale), 'it');

        return number_format(
            $value,
            $precision,
            $usesCommaDecimal ? ',' : '.',
            $usesCommaDecimal ? '.' : ',',
        );
    }
}

<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateHelper
{
    public static function parse($value): array
    {
        $date = Carbon::parse($value);

        return [
            'year' => $date->year,
            'month' => $date->month,
            'day' => $date->day,
            'time' => $date->format('H:i'),
        ];
    }

    public static function format($value): string
    {
        return Carbon::parse($value)->format('d-m-Y H:i');
    }

    public static function monthName(int $month): string
    {
        if ($month < 1 || $month > 12) {
            return '';
        }

        return ucfirst(Carbon::create(2000, $month, 1)
            ->locale(app()->getLocale())
            ->translatedFormat('F'));
    }
}

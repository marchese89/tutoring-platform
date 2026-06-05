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

    public static function formatItalianDate($value): string
    {
        return Carbon::parse($value)->format('d/m/Y');
    }

    public static function monthName(int $month): string
    {
        return match ($month) {
            1 => 'Gennaio',
            2 => 'Febbraio',
            3 => 'Marzo',
            4 => 'Aprile',
            5 => 'Maggio',
            6 => 'Giugno',
            7 => 'Luglio',
            8 => 'Agosto',
            9 => 'Settembre',
            10 => 'Ottobre',
            11 => 'Novembre',
            12 => 'Dicembre',
            default => '',
        };
    }
}

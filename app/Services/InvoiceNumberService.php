<?php

namespace App\Services;

use App\Models\InvoiceSequence;
use Illuminate\Support\Facades\DB;

class InvoiceNumberService
{
    public function next(?int $year = null): int
    {
        $year ??= now()->year;

        return DB::transaction(function () use ($year) {
            InvoiceSequence::query()->insertOrIgnore([
                'year' => $year,
                'last_number' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $sequence = InvoiceSequence::query()
                ->lockForUpdate()
                ->findOrFail($year);

            $sequence->increment('last_number');
            $sequence->refresh();

            return $sequence->last_number;
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoice_sequences', function (Blueprint $table) {
            $table->unsignedSmallInteger('year')->primary();
            $table->unsignedInteger('last_number')->default(0);
            $table->timestamps();
        });

        DB::table('invoices')
            ->whereNotNull('issued_at')
            ->whereNotNull('number')
            ->get(['issued_at', 'number'])
            ->groupBy(fn ($invoice) => Carbon::parse($invoice->issued_at)->year)
            ->map(fn ($invoices) => $invoices->max('number'))
            ->each(function ($lastNumber, $year) {
                DB::table('invoice_sequences')->insert([
                    'year' => $year,
                    'last_number' => $lastNumber,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_sequences');
    }
};

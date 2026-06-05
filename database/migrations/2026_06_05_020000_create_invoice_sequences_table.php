<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
            ->selectRaw('YEAR(issued_at) as invoice_year, MAX(number) as last_number')
            ->groupByRaw('YEAR(issued_at)')
            ->get()
            ->each(function ($sequence) {
                DB::table('invoice_sequences')->insert([
                    'year' => $sequence->invoice_year,
                    'last_number' => $sequence->last_number,
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

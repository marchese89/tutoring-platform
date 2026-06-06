<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->foreignId('student_id')
                ->nullable()
                ->after('order_id')
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('payment_transaction_id')
                ->nullable()
                ->after('student_id')
                ->constrained()
                ->nullOnDelete();

            $table->unique('payment_transaction_id');
            $table->index(['student_id', 'issued_at']);
        });

        DB::table('invoices')
            ->whereNotNull('order_id')
            ->whereNull('student_id')
            ->orderBy('id')
            ->chunkById(100, function ($invoices) {
                foreach ($invoices as $invoice) {
                    $studentId = DB::table('orders')
                        ->where('id', $invoice->order_id)
                        ->value('student_id');

                    if ($studentId) {
                        DB::table('invoices')
                            ->where('id', $invoice->id)
                            ->update(['student_id' => $studentId]);
                    }
                }
            });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropIndex(['student_id', 'issued_at']);
            $table->dropUnique(['payment_transaction_id']);
            $table->dropConstrainedForeignId('payment_transaction_id');
            $table->dropConstrainedForeignId('student_id');
        });
    }
};

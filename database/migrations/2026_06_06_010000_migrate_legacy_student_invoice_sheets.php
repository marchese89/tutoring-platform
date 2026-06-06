<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('student_invoices') && Schema::hasTable('invoice_sheets')) {
            DB::table('student_invoices')
                ->join('invoice_sheets', 'invoice_sheets.id', '=', 'student_invoices.invoice_sheet_id')
                ->select([
                    'student_invoices.student_id',
                    'invoice_sheets.file_path',
                    'invoice_sheets.issued_at',
                    'student_invoices.created_at',
                    'student_invoices.updated_at',
                ])
                ->orderBy('student_invoices.id')
                ->chunk(100, function ($rows) {
                    foreach ($rows as $row) {
                        if (! $row->file_path) {
                            continue;
                        }

                        $exists = DB::table('invoices')
                            ->where('student_id', $row->student_id)
                            ->where('file_path', $row->file_path)
                            ->exists();

                        if ($exists) {
                            continue;
                        }

                        DB::table('invoices')->insert([
                            'number' => null,
                            'issued_at' => $row->issued_at,
                            'order_id' => null,
                            'student_id' => $row->student_id,
                            'payment_transaction_id' => null,
                            'file_path' => $row->file_path,
                            'created_at' => $row->created_at ?? now(),
                            'updated_at' => $row->updated_at ?? now(),
                        ]);
                    }
                });
        }

        Schema::dropIfExists('student_invoices');
        Schema::dropIfExists('invoice_sheets');
    }

    public function down(): void
    {
        Schema::create('invoice_sheets', function (Blueprint $table) {
            $table->id();
            $table->string('file_path')->nullable();
            $table->timestamp('issued_at')->nullable();
            $table->timestamps();
        });

        Schema::create('student_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('invoice_sheet_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }
};

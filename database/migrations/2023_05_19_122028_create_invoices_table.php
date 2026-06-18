<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->integer('number');
            $table->timestamp('issued_at');
            $table->foreignId('order_id')->nullable()->unique()->constrained()->nullOnDelete();
            $table->foreignId('student_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('payment_transaction_id')->nullable()->unique()->constrained()->nullOnDelete();
            $table->string('source', 20)->default('order');
            $table->unsignedInteger('total_amount');
            $table->char('currency', 3)->default('eur');
            $table->json('customer_snapshot');
            $table->json('line_items');
            $table->string('note')->nullable();
            $table->string('file_path');
            $table->timestamps();

            $table->index(['student_id', 'issued_at']);
            $table->index(['source', 'issued_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};

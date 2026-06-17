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
            $table->integer('number')->nullable();
            $table->timestamp('issued_at')->nullable();
            $table->foreignId('order_id')->nullable()->unique()->constrained()->nullOnDelete();
            $table->foreignId('student_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('payment_transaction_id')->nullable()->unique()->constrained()->nullOnDelete();
            $table->string('source', 20)->default('order');
            $table->unsignedInteger('total_amount')->nullable();
            $table->char('currency', 3)->default('eur');
            $table->json('customer_snapshot')->nullable();
            $table->json('line_items')->nullable();
            $table->string('note')->nullable();
            $table->string('file_path')->nullable();
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

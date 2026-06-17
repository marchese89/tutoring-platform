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
        Schema::create('chats', function (Blueprint $table) {

            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedTinyInteger('product_type');
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->unique(['product_id', 'product_type', 'student_id']);
            $table->index(['student_id', 'product_type']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chats');
    }
};

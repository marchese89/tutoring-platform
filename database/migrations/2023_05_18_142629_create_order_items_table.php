<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('product_id');
            $table->unsignedTinyInteger('product_type');
            $table->integer('price');
            $table->string('description')->nullable();
            $table->timestamps();

            $table->unique(['order_id', 'product_id', 'product_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};

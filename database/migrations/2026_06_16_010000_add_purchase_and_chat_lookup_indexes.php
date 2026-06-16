<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->index(['product_type', 'product_id']);
        });

        Schema::table('chats', function (Blueprint $table) {
            $table->index(['student_id', 'product_type']);
        });
    }

    public function down(): void
    {
        Schema::table('chats', function (Blueprint $table) {
            $table->dropIndex(['student_id', 'product_type']);
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropIndex(['product_type', 'product_id']);
        });
    }
};

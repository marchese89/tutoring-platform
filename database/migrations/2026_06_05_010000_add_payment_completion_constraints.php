<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payment_transactions', function (Blueprint $table) {
            $table->timestamp('receipt_sent_at')->nullable()->after('completed_at');
            $table->unique('order_id');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->unique('order_id');
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropUnique(['order_id']);
        });

        Schema::table('payment_transactions', function (Blueprint $table) {
            $table->dropUnique(['order_id']);
            $table->dropColumn('receipt_sent_at');
        });
    }
};

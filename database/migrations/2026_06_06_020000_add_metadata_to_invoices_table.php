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
            $table->string('source', 20)->default('order')->after('payment_transaction_id');
            $table->unsignedInteger('total_amount')->nullable()->after('source');
            $table->char('currency', 3)->default('eur')->after('total_amount');
            $table->json('customer_snapshot')->nullable()->after('currency');
            $table->json('line_items')->nullable()->after('customer_snapshot');
            $table->string('note')->nullable()->after('line_items');
            $table->index(['source', 'issued_at']);
        });

        DB::table('invoices')
            ->orderBy('id')
            ->chunkById(100, function ($invoices) {
                foreach ($invoices as $invoice) {
                    $source = $invoice->order_id ? 'order' : 'extra';
                    $total = null;
                    $lineItems = null;

                    if ($invoice->order_id) {
                        $items = DB::table('order_items')
                            ->where('order_id', $invoice->order_id)
                            ->get(['description', 'price']);

                        $total = (int) $items->sum('price') * 100;
                        $lineItems = $items->map(fn ($item) => [
                            'description' => $item->description,
                            'unit_price' => (int) $item->price * 100,
                            'quantity' => 1,
                            'total' => (int) $item->price * 100,
                        ])->values()->all();
                    }

                    DB::table('invoices')
                        ->where('id', $invoice->id)
                        ->update([
                            'source' => $source,
                            'total_amount' => $total,
                            'currency' => 'eur',
                            'line_items' => $lineItems ? json_encode($lineItems) : null,
                        ]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropIndex(['source', 'issued_at']);
            $table->dropColumn([
                'source',
                'total_amount',
                'currency',
                'customer_snapshot',
                'line_items',
                'note',
            ]);
        });
    }
};

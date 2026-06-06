<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'issued_at',
        'order_id',
        'student_id',
        'payment_transaction_id',
        'source',
        'total_amount',
        'currency',
        'customer_snapshot',
        'line_items',
        'note',
        'file_path',
    ];

    protected $casts = [
        'number' => 'integer',
        'total_amount' => 'integer',
        'issued_at' => 'datetime',
        'customer_snapshot' => 'array',
        'line_items' => 'array',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function paymentTransaction(): BelongsTo
    {
        return $this->belongsTo(PaymentTransaction::class);
    }
}

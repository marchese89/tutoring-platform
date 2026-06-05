<?php

namespace App\Models;

use App\Enums\PaymentPurpose;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_id',
        'stripe_payment_intent_id',
        'purpose',
        'amount',
        'currency',
        'status',
        'context',
        'completed_at',
        'receipt_sent_at',
    ];

    protected $casts = [
        'purpose' => PaymentPurpose::class,
        'context' => 'array',
        'completed_at' => 'datetime',
        'receipt_sent_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function isCompleted(): bool
    {
        return $this->completed_at !== null;
    }
}

<?php

namespace App\Payments;

class PaymentIntentData
{
    public function __construct(
        public readonly string $id,
        public readonly string $clientSecret,
        public readonly string $status,
        public readonly int $amount,
        public readonly string $currency,
    ) {}

    public function succeeded(): bool
    {
        return $this->status === 'succeeded';
    }
}

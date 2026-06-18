<?php

namespace App\Payments;

use App\Enums\PaymentStatus;

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
        return $this->status === PaymentStatus::SUCCEEDED->value;
    }
}

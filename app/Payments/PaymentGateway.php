<?php

namespace App\Payments;

use App\Models\User;

interface PaymentGateway
{
    public function create(User $user, int $amount, array $metadata): PaymentIntentData;

    public function retrieve(User $user, string $paymentIntentId): PaymentIntentData;
}

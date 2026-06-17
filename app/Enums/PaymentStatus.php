<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case CREATING = 'creating';
    case FAILED = 'failed';
    case REQUIRES_PAYMENT_METHOD = 'requires_payment_method';
    case SUCCEEDED = 'succeeded';
}

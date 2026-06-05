<?php

namespace App\Enums;

enum PaymentPurpose: string
{
    case CHECKOUT = 'checkout';
    case EXTRA = 'extra';
}

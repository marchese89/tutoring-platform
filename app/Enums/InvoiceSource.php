<?php

namespace App\Enums;

enum InvoiceSource: string
{
    case ORDER = 'order';
    case EXTRA = 'extra';
}

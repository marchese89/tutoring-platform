<?php

namespace App\Support;

use Illuminate\Validation\Rules\Password;

final class PasswordRequirements
{
    public static function rule(): Password
    {
        return Password::min(10)
            ->mixedCase()
            ->numbers()
            ->symbols();
    }
}

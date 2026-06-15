<?php

namespace App\Http\Requests;

use App\Support\PasswordRequirements;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAccountPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', PasswordRequirements::rule()],
        ];
    }

    public function attributes(): array
    {
        return [
            'current_password' => 'password attuale',
            'password' => 'nuova password',
            'password_confirmation' => 'conferma password',
        ];
    }

    public function messages(): array
    {
        return [
            'current_password.current_password' => 'La password attuale non è corretta.',
        ];
    }
}

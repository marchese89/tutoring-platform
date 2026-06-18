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
            'current_password' => __('account.credentials.current_password'),
            'password' => __('account.credentials.new_password'),
            'password_confirmation' => __('account.credentials.password_confirmation'),
        ];
    }

    public function messages(): array
    {
        return [
            'current_password.current_password' => __('account.credentials.current_password_invalid'),
        ];
    }
}

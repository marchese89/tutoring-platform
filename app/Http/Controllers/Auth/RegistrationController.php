<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Http\Requests\RegisterUserRequest;

class RegistrationController extends Controller
{
    public function store(RegisterUserRequest $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'address' => 'required|string|max:255',
            'house_number' => 'required|string|max:10',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
            'tax_code' => 'required|string|max:16',
        ]);

        DB::transaction(function () use ($data) {

            $user = User::create([
                'name' => $data['first_name'],
                'surname' => $data['last_name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'activation_code' => Str::random(6),
                'remember_token' => Str::random(10),
            ]);

            $user->student()->create([
                'street' => $data['address'],
                'house_number' => $data['house_number'],
                'city' => $data['city'],
                'province' => $data['province'],
                'postal_code' => $data['postal_code'],
                'tax_code' => $data['tax_code'],
                'remember_token' => Str::random(10),
            ]);
        });

        return redirect()->route('registration.success');
    }
}

<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function profile(Request $request): View
    {
        $user = $request->user()->load('student');

        return view('student.profile', [
            'name' => $user->name,
            'surname' => $user->surname,
            'address' => $user->student->only([
                'street',
                'house_number',
                'city',
                'province',
                'postal_code',
            ]),
        ]);
    }

    public function updateAddress(Request $request): RedirectResponse
    {
        $validated = $request->validate(
            [
                'street' => ['required', 'string', 'max:255'],
                'house_number' => ['required', 'string', 'max:6'],
                'city' => ['required', 'string', 'max:255'],
                'province' => ['required', 'string', 'max:2'],
                'postal_code' => ['required', 'string', 'max:5'],
            ],
            [],
            [
                'street' => 'indirizzo',
                'house_number' => 'numero civico',
                'city' => 'città',
                'province' => 'provincia',
                'postal_code' => 'CAP',
            ]
        );

        $request->user()->student->update($validated);

        return redirect()->route('student.account.profile');
    }
}

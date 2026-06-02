<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Utility\Cart;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Password;


class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function login(Request $request)
    {

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);



        if (Auth::guard('web')->attempt($credentials)) {

            $request->session()->regenerate();

            if ($request->user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($request->user()->role === 'student') {
                Session::put('cart', new Cart());
                if (request('return') === '1') {
                    return redirect()->route('lesson-requests.create');
                } else {
                    return redirect()->route('student.dashboard');
                }
            }
        }

        return back()->withErrors([
            'email' => 'Credenziali non corrette',
        ])->onlyInput('email');
    }

    public function sendPasswordResetLink(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status == Password::RESET_LINK_SENT) {
            return back()->withSuccess('Link di reset inviato via email');
        }

        return back()->withErrors([
            'email' => 'Email non valida o non presente'
        ]);
    }
}

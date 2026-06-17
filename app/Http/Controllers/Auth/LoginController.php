<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Utility\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;

class LoginController extends Controller
{
    public function show(Request $request)
    {
        return view('auth.login', [
            'returnToLessonRequest' => $request->boolean('back'),
        ]);
    }

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

            if ($request->user()->isAdmin()) {
                return redirect()->route('admin.dashboard');
            } elseif ($request->user()->isStudent()) {
                $request->session()->put('cart', new Cart);
                if ($request->boolean('return')) {
                    return redirect()->route('lesson-requests.create');
                } else {
                    return redirect()->route('student.dashboard');
                }
            }
        }

        return back()->withErrors([
            'email' => __('auth.failed'),
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
            return back()->withSuccess(__($status));
        }

        return back()->withErrors([
            'email' => __($status),
        ]);
    }
}

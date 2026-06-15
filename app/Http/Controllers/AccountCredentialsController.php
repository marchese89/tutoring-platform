<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateAccountEmailRequest;
use App\Http\Requests\UpdateAccountPasswordRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AccountCredentialsController extends Controller
{
    public function showAdmin(Request $request): View
    {
        return view('admin.settings.credentials', [
            'email' => $request->user()->email,
        ]);
    }

    public function showStudent(Request $request): View
    {
        return view('student.credentials', [
            'email' => $request->user()->email,
        ]);
    }

    public function updateEmail(UpdateAccountEmailRequest $request): RedirectResponse
    {
        $request->user()->update($request->validated());

        return redirect()
            ->route($this->credentialsRoute($request))
            ->withSuccess(__('account.credentials.email_updated'));
    }

    public function updatePassword(UpdateAccountPasswordRequest $request): RedirectResponse
    {
        $request->user()->update([
            'password' => Hash::make($request->validated('password')),
        ]);

        return redirect()
            ->route($this->credentialsRoute($request))
            ->withSuccess(__('account.credentials.password_updated'));
    }

    private function credentialsRoute(Request $request): string
    {
        return $request->user()->role === 'admin'
            ? 'admin.account.credentials'
            : 'student.account.credentials';
    }
}

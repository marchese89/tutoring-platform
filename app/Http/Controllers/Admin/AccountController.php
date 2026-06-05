<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Admin;
use App\Models\Certificate;
use App\Support\UploadRules;

class AccountController extends Controller
{
    public function certificatesIndex()
    {
        $certificates = Certificate::orderBy('id')->get();

        return view('admin.settings.certificates', compact('certificates'));
    }

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'file' => UploadRules::image(),
        ]);

        $admin = auth()->user()->admin;

        if ($admin->photo_path) {
            $oldPath = public_path($admin->photo_path);
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }

        $file = $request->file('file');
        $name = uniqid() . '.' . $file->getClientOriginalExtension();

        $file->move(public_path('files/photo_admin'), $name);

        $path = '/files/photo_admin/' . $name;

        $admin->photo_path = $path;
        $admin->save();

        return redirect()->route('admin.account.photo');
    }

    public function updateCertificateFile(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:certificates,id',
            'file' => UploadRules::pdf(),
        ]);

        $certificate = Certificate::findOrFail($request->id);

        if ($certificate->file_path) {
            $oldPath = public_path($certificate->file_path);
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }

        $file = $request->file('file');
        $name = uniqid() . '.' . $file->getClientOriginalExtension();

        $file->move(public_path('files/cert_admin'), $name);

        $path = '/files/cert_admin/' . $name;

        $certificate->file_path = $path;
        $certificate->save();

        return redirect()->route('admin.account.certificates.index');
    }

    public function storeCertificateUpload(Request $request)
    {
        $request->validate([
            'file' => UploadRules::pdf(),
        ]);

        if ($request->session()->has('uploaded_certificate_file')) {
            $oldPath = public_path($request->session()->get('uploaded_certificate_file'));
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }

        $file = $request->file('file');
        $name = uniqid() . '.' . $file->getClientOriginalExtension();

        $file->move(public_path('files/cert_admin'), $name);

        $path = '/files/cert_admin/' . $name;

        $request->session()->put('uploaded_certificate_file', $path);

        return redirect()->route('admin.account.certificates.create');
    }

    public function updateCertificateName(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:certificates,id',
            'name' => 'required|string|max:255'
        ]);

        $certificate = Certificate::findOrFail($request->id);
        $certificate->name = $request->name;
        $certificate->save();

        return redirect()->route('admin.account.certificates.index');
    }

    public function updateVatNumber(Request $request)
    {
        $request->validate([
            'vat_number' => 'required|string|max:20'
        ]);

        $admin = auth()->user()->admin;
        $admin->vat_number = $request->vat_number;
        $admin->save();

        return redirect()->route('admin.account.vat-number');
    }

    public function updateAddress(Request $request)
    {
        $validated = $request->validate(
            [
                'street' => 'required|string|max:255',
                'house_number' => 'required|string|max:10',
                'city' => 'required|string|max:100',
                'province' => 'required|string|max:100',
                'postal_code' => 'required|string|max:10',
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

        $admin = auth()->user()->admin;

        $admin->update($validated);

        return redirect()->route('admin.account.address');
    }

    public function destroyCertificate(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:certificates,id'
        ]);

        $certificate = Certificate::findOrFail($request->id);

        if ($certificate->file_path) {
            $oldPath = public_path($certificate->file_path);
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }

        $certificate->delete();

        return redirect()->route('admin.account.certificates.index');
    }

    public function destroyCertificateUpload(Request $request)
    {
        if ($request->session()->has('uploaded_certificate_file')) {
            $oldPath = public_path($request->session()->get('uploaded_certificate_file'));
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
            $request->session()->forget('uploaded_certificate_file');
        }

        return redirect()->route('admin.account.certificates.create');
    }

    public function storeCertificate(Request $request)
    {
        if (!$request->session()->has('uploaded_certificate_file')) {
            return redirect()
                ->route('admin.account.certificates.create')
                ->withErrors(['file' => 'Carica un file prima di salvare il certificato.']);
        }

        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $certificate = new Certificate();
        $certificate->name = $request->name;
        $certificate->file_path = $request->session()->get('uploaded_certificate_file');

        $request->session()->forget('uploaded_certificate_file');

        $certificate->save();

        return redirect()->route('admin.account.certificates.index');
    }

    public function updateEmail(Request $request)
    {
        $validated = $request->validate([
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($request->user()->id),
            ],
        ]);

        $user = $request->user();
        $user->email = $validated['email'];
        $user->save();

        return redirect()->route('admin.account.credentials');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate(
            [
                'current_password' => 'required',
                'password' => 'required|min:6|confirmed',
            ],
            [],
            [
                'current_password' => 'password attuale',
                'password' => 'nuova password',
                'password_confirmation' => 'conferma password',
            ]
        );

        $user = $request->user();

        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors([
                'current_password' => 'Password attuale errata',
            ]);
        }

        $user->password = Hash::make($validated['password']);
        $user->save();

        return redirect()->route('admin.account.credentials')->withSuccess('Password modificata con successo');
    }
}

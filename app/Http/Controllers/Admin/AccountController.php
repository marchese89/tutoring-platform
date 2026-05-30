<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Admin;
use App\Models\Certificate;

class AccountController extends Controller
{
    // =========================
    // FOTO PROFILO
    // =========================
    public function updatePhoto(Request $request)
    {
        $request->validate([
            'file' => 'required|image'
        ]);

        $admin = auth()->user()->admin;

        // elimina vecchia foto
        if ($admin->photo) {
            $oldPath = public_path($admin->photo);
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }

        $file = $request->file('file');
        $name = uniqid() . '.' . $file->getClientOriginalExtension();

        $file->move(public_path('files/photo_admin'), $name);

        $path = '/files/photo_admin/' . $name;

        $admin->photo = $path;
        $admin->save();

        return redirect()->route('admin.account.photo');
    }

    // =========================
    // UPLOAD CERTIFICATO
    // =========================
    public function updateCertificateFile(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:certificates,id',
            'file' => 'required|file'
        ]);

        $certificate = Certificate::findOrFail($request->id);

        if ($certificate->percorso_file) {
            $oldPath = public_path($certificate->percorso_file);
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }

        $file = $request->file('file');
        $name = uniqid() . '.' . $file->getClientOriginalExtension();

        $file->move(public_path('files/cert_admin'), $name);

        $path = '/files/cert_admin/' . $name;

        $certificate->percorso_file = $path;
        $certificate->save();

        return redirect()->route('admin.account.certificates.index');
    }

    // =========================
    // UPLOAD CERTIFICATO (SESSIONE)
    // =========================
    public function storeCertificateUpload(Request $request)
    {
        $request->validate([
            'file' => 'required|file'
        ]);

        if ($request->session()->has('uploaded_cert')) {
            $oldPath = public_path($request->session()->get('uploaded_cert'));
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }

        $file = $request->file('file');
        $name = uniqid() . '.' . $file->getClientOriginalExtension();

        $file->move(public_path('files/cert_admin'), $name);

        $path = '/files/cert_admin/' . $name;

        $request->session()->put('uploaded_cert', $path);

        return redirect()->route('admin.account.certificates.create');
    }

    // =========================
    // MODIFICA NOME CERTIFICATO
    // =========================
    public function updateCertificateName(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:certificates,id',
            'nome' => 'required|string|max:255'
        ]);

        $certificate = Certificate::findOrFail($request->id);
        $certificate->nome = $request->nome;
        $certificate->save();

        return redirect()->route('admin.account.certificates.index');
    }

    // =========================
    // PARTITA IVA
    // =========================
    public function updateVatNumber(Request $request)
    {
        $request->validate([
            'piva' => 'required|string|max:20'
        ]);

        $admin = auth()->user()->admin;
        $admin->piva = $request->piva;
        $admin->save();

        return redirect()->route('admin.account.vat-number');
    }

    // =========================
    // INDIRIZZO
    // =========================
    public function updateAddress(Request $request)
    {
        $request->validate([
            'inputIndirizzo' => 'required|string|max:255',
            'inputNumeroCivico' => 'required|string|max:10',
            'inputCitta' => 'required|string|max:100',
            'inputProvincia' => 'required|string|max:100',
            'inputCAP' => 'required|string|max:10'
        ]);

        $admin = auth()->user()->admin;

        $admin->street = $request->inputIndirizzo;
        $admin->house_number = $request->inputNumeroCivico;
        $admin->city = $request->inputCitta;
        $admin->province = $request->inputProvincia;
        $admin->postal_code = $request->inputCAP;

        $admin->save();

        return redirect()->route('admin.account.address');
    }

    // =========================
    // ELIMINA CERTIFICATO
    // =========================
    public function destroyCertificate(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:certificates,id'
        ]);

        $certificate = Certificate::findOrFail($request->id);

        if ($certificate->percorso_file) {
            $oldPath = public_path($certificate->percorso_file);
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }

        $certificate->delete();

        return redirect()->route('admin.account.certificates.index');
    }

    // =========================
    // ELIMINA CERTIFICATO SESSIONE
    // =========================
    public function destroyCertificateUpload(Request $request)
    {
        if ($request->session()->has('uploaded_cert')) {
            $oldPath = public_path($request->session()->get('uploaded_cert'));
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
            $request->session()->forget('uploaded_cert');
        }

        return redirect()->route('admin.account.certificates.create');
    }

    // =========================
    // AGGIUNGI CERTIFICATO
    // =========================
    public function storeCertificate(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255'
        ]);

        $cert = new Certificate();
        $cert->nome = $request->nome;
        $cert->percorso_file = $request->session()->get('uploaded_cert');

        $request->session()->forget('uploaded_cert');

        $cert->save();

        return redirect()->route('admin.account.certificates.index');
    }

    // =========================
    // EMAIL
    // =========================
    public function updateEmail(Request $request)
    {
        $request->validate([
            'inputEmail' => 'required|email|unique:users,email'
        ]);

        $user = auth()->user();
        $user->email = $request->input('inputEmail');
        $user->save();

        return redirect()->route('admin.account.credentials');
    }

    // =========================
    // PASSWORD
    // =========================
    public function updatePassword(Request $request)
    {
        $request->validate([
            'inputPassword_old' => 'required',
            'inputPassword' => 'required|min:6|confirmed'
        ]);

        $user = auth()->user();

        if (!Hash::check($request->input('inputPassword_old'), $user->password)) {
            return back()->withErrors([
                'pass0' => 'Password attuale errata'
            ]);
        }

        $user->password = Hash::make($request->input('inputPassword'));
        $user->save();

        return redirect()->route('admin.account.credentials')->withSuccess('Password modificata con successo');
    }
}

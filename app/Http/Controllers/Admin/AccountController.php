<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Support\PublicUploadStorage;
use App\Support\UploadRules;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function address(Request $request): View
    {
        return view('admin.settings.address', [
            'address' => $request->user()->admin->only([
                'street',
                'house_number',
                'city',
                'province',
                'postal_code',
            ]),
        ]);
    }

    public function photo(Request $request): View
    {
        return view('admin.settings.photo', [
            'photoPath' => $request->user()->admin->photo_path,
        ]);
    }

    public function vatNumber(Request $request): View
    {
        return view('admin.settings.vat-number', [
            'vatNumber' => $request->user()->admin->vat_number,
        ]);
    }

    public function createCertificate(Request $request)
    {
        $uploadedCertificateFile = $request->session()->get('uploaded_certificate_file');

        return view('admin.settings.create-certificate', [
            'uploadedCertificateFile' => $uploadedCertificateFile,
        ]);
    }

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

        $admin = $request->user()->admin;

        $oldPath = $admin->photo_path;
        $admin->photo_path = PublicUploadStorage::store(
            $request->file('file'),
            'admin/photos'
        );
        $admin->save();

        PublicUploadStorage::delete($oldPath);

        return redirect()->route('admin.account.photo');
    }

    public function updateCertificateFile(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:certificates,id',
            'file' => UploadRules::pdf(),
        ]);

        $certificate = Certificate::findOrFail($request->id);

        $oldPath = $certificate->file_path;
        $certificate->file_path = PublicUploadStorage::store(
            $request->file('file'),
            'certificates'
        );
        $certificate->save();

        PublicUploadStorage::delete($oldPath);

        return redirect()->route('admin.account.certificates.index');
    }

    public function storeCertificateUpload(Request $request)
    {
        $request->validate([
            'file' => UploadRules::pdf(),
        ]);

        $oldPath = $request->session()->get('uploaded_certificate_file');
        $path = PublicUploadStorage::store(
            $request->file('file'),
            'certificates'
        );

        $request->session()->put('uploaded_certificate_file', $path);

        PublicUploadStorage::delete($oldPath);

        return redirect()->route('admin.account.certificates.create');
    }

    public function updateCertificateName(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:certificates,id',
            'name' => 'required|string|max:255',
        ]);

        $certificate = Certificate::findOrFail($request->id);
        $certificate->name = $request->name;
        $certificate->save();

        return redirect()->route('admin.account.certificates.index');
    }

    public function updateVatNumber(Request $request)
    {
        $request->validate([
            'vat_number' => 'required|string|max:20',
        ]);

        $admin = $request->user()->admin;
        $admin->vat_number = $request->vat_number;
        $admin->save();

        return redirect()->route('admin.account.vat-number');
    }

    public function updateAddress(Request $request)
    {
        $validated = $request->validate([
            'street' => 'required|string|max:255',
            'house_number' => 'required|string|max:6',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:2',
            'postal_code' => 'required|string|max:5',
        ]);

        $admin = $request->user()->admin;

        $admin->update($validated);

        return redirect()->route('admin.account.address');
    }

    public function destroyCertificate(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:certificates,id',
        ]);

        $certificate = Certificate::findOrFail($request->id);

        PublicUploadStorage::delete($certificate->file_path);

        $certificate->delete();

        return redirect()->route('admin.account.certificates.index');
    }

    public function destroyCertificateUpload(Request $request)
    {
        if ($request->session()->has('uploaded_certificate_file')) {
            PublicUploadStorage::delete(
                $request->session()->get('uploaded_certificate_file')
            );
            $request->session()->forget('uploaded_certificate_file');
        }

        return redirect()->route('admin.account.certificates.create');
    }

    public function storeCertificate(Request $request)
    {
        if (! $request->session()->has('uploaded_certificate_file')) {
            return redirect()
                ->route('admin.account.certificates.create')
                ->withErrors(['file' => __('account.certificates.file_required')]);
        }

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $certificate = new Certificate;
        $certificate->name = $request->name;
        $certificate->file_path = $request->session()->get('uploaded_certificate_file');

        $request->session()->forget('uploaded_certificate_file');

        $certificate->save();

        return redirect()->route('admin.account.certificates.index');
    }
}

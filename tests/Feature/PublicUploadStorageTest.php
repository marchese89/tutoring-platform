<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Certificate;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PublicUploadStorageTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_photo_uses_generated_image_extension(): void
    {
        Storage::fake('public');
        $adminUser = $this->createAdmin();

        $response = $this->actingAs($adminUser)
            ->post(route('admin.account.photo.update'), [
                'file' => UploadedFile::fake()->image('profile.jpg'),
            ]);

        $response->assertRedirect(route('admin.account.photo'));

        $photoPath = $adminUser->admin->fresh()->photo_path;

        $this->assertStringStartsWith('/storage/admin/photos/', $photoPath);
        $this->assertStringNotContainsString('profile.jpg', $photoPath);
        $this->assertStringEndsWith('.jpg', $photoPath);
        Storage::disk('public')->assertExists(substr($photoPath, 9));
    }

    public function test_certificate_uses_generated_pdf_extension(): void
    {
        Storage::fake('public');
        $adminUser = $this->createAdmin();

        $response = $this->actingAs($adminUser)
            ->post(route('admin.account.certificates.uploads.store'), [
                'file' => UploadedFile::fake()->create(
                    'certificate.pdf',
                    10,
                    'application/pdf'
                ),
            ]);

        $response->assertRedirect(route('admin.account.certificates.create'));

        $certificatePath = session('uploaded_certificate_file');

        $this->assertStringStartsWith('/storage/certificates/', $certificatePath);
        $this->assertStringNotContainsString('certificate.pdf', $certificatePath);
        $this->assertStringEndsWith('.pdf', $certificatePath);
        Storage::disk('public')->assertExists(substr($certificatePath, 9));

        $this->actingAs($adminUser)
            ->withSession([
                'locale' => 'en',
                'uploaded_certificate_file' => $certificatePath,
            ])
            ->get(route('admin.account.certificates.create'))
            ->assertOk()
            ->assertSee('Certificate file')
            ->assertSee('Certificate details')
            ->assertSee('src="'.$certificatePath.'#view=FitH"', false)
            ->assertSee('pdf-viewer--compact', false)
            ->assertSee(route('admin.account.certificates.store'));
    }

    public function test_certificate_list_uses_compact_pdf_viewer(): void
    {
        $adminUser = $this->createAdmin();
        $certificate = Certificate::create([
            'name' => 'Laravel certificate',
            'file_path' => '/storage/certificates/laravel.pdf',
        ]);

        $this->actingAs($adminUser)
            ->withSession(['locale' => 'en'])
            ->get(route('admin.account.certificates.index'))
            ->assertOk()
            ->assertSee('Update certificates')
            ->assertSee('Certificate #'.$certificate->id)
            ->assertSee('Replace file')
            ->assertSee('Laravel certificate')
            ->assertSee('src="'.$certificate->file_path.'#view=FitH"', false)
            ->assertSee('pdf-viewer--compact', false);
    }

    public function test_admin_certificate_list_is_paginated(): void
    {
        $adminUser = $this->createAdmin();

        for ($index = 1; $index <= 7; $index++) {
            Certificate::factory()->create([
                'name' => sprintf('Admin certificate %02d', $index),
            ]);
        }

        $this->actingAs($adminUser)
            ->get(route('admin.account.certificates.index'))
            ->assertOk()
            ->assertSee('Admin certificate 01')
            ->assertDontSee('Admin certificate 07')
            ->assertSee('page=2', false);
    }

    public function test_admin_photo_page_displays_the_current_photo(): void
    {
        $adminUser = $this->createAdmin();
        $adminUser->admin->update([
            'photo_path' => '/storage/admin/photos/profile.jpg',
        ]);

        $this->actingAs($adminUser)
            ->get(route('admin.account.photo'))
            ->assertOk()
            ->assertSee('src="/storage/admin/photos/profile.jpg"', false)
            ->assertSee('class="admin-photo-preview shadow-sm border"', false)
            ->assertDontSee('style="max-width: 300px', false);
    }

    public function test_replacing_certificate_deletes_previous_public_file(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('certificates/old.pdf', 'old');
        $adminUser = $this->createAdmin();
        $certificate = Certificate::create([
            'name' => 'Existing certificate',
            'file_path' => '/storage/certificates/old.pdf',
        ]);

        $response = $this->actingAs($adminUser)
            ->post(route('admin.account.certificates.file.update'), [
                'id' => $certificate->id,
                'file' => UploadedFile::fake()->create(
                    'replacement.pdf',
                    10,
                    'application/pdf'
                ),
            ]);

        $response->assertRedirect(route('admin.account.certificates.index'));
        Storage::disk('public')->assertMissing('certificates/old.pdf');

        $newPath = $certificate->fresh()->file_path;

        Storage::disk('public')->assertExists(substr($newPath, 9));
    }

    private function createAdmin(): User
    {
        $user = User::factory()->create(['role' => 'admin']);

        Admin::create(['user_id' => $user->id]);

        return $user;
    }
}

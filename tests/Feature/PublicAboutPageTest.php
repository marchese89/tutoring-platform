<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Certificate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicAboutPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_about_page_lists_certificates_prepared_by_controller(): void
    {
        Admin::factory()->create([
            'photo_path' => '/files/tutor.jpg',
        ]);

        Certificate::create([
            'name' => 'PHP Certificate',
            'file_path' => '/storage/certificates/php.pdf',
        ]);

        $this->withSession(['locale' => 'en'])
            ->get(route('about'))
            ->assertOk()
            ->assertSee('A technical learning path built for real understanding.')
            ->assertSee('Computer science background with attention to student reasoning.')
            ->assertSee('/files/tutor.jpg', false)
            ->assertSee('1 certificate published')
            ->assertSee('PHP Certificate')
            ->assertSee('src="/storage/certificates/php.pdf#view=FitH"', false)
            ->assertSee('pdf-viewer--compact', false);
    }
}

<?php

namespace Tests\Feature;

use App\Models\Certificate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicAboutPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_about_page_lists_certificates_prepared_by_controller(): void
    {
        Certificate::create([
            'name' => 'PHP Certificate',
            'file_path' => '/storage/certificates/php.pdf',
        ]);

        $this->get(route('about'))
            ->assertOk()
            ->assertSee('PHP Certificate')
            ->assertSee('src="/storage/certificates/php.pdf#view=FitH"', false)
            ->assertSee('pdf-viewer--compact', false);
    }
}

<?php

namespace Tests\Feature;

use App\Mail\LessonRequestFulfilledMail;
use App\Mail\NewStudentRequestMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MailAndInvoiceLocalizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_email_views_follow_the_application_locale(): void
    {
        app()->setLocale('en');

        $this->assertStringContainsString('New lesson request', (new NewStudentRequestMail)->render());
        $this->assertStringContainsString('Open the admin panel to view it.', (new NewStudentRequestMail)->render());

        $this->assertStringContainsString('Request completed', (new LessonRequestFulfilledMail)->render());
        $this->assertStringContainsString('You can now view the quote on the site.', (new LessonRequestFulfilledMail)->render());

        $resetHtml = view('emails.reset-password', ['url' => 'https://example.test/reset'])->render();

        $this->assertStringContainsString('Reset password', $resetHtml);
        $this->assertStringContainsString('This link will expire in 60 minutes.', $resetHtml);
    }

    public function test_invoice_template_follows_the_application_locale(): void
    {
        app()->setLocale('en');

        $html = view('invoices.invoice', [
            'user' => User::factory()->make([
                'name' => 'Mario',
                'surname' => 'Rossi',
            ]),
            'customer' => (object) [
                'street' => 'Customer street',
                'house_number' => '1',
                'postal_code' => '00100',
                'city' => 'Rome',
                'province' => 'RM',
                'tax_code' => 'RSSMRA80A01H501U',
            ],
            'admin' => User::factory()->make([
                'name' => 'Damiano',
                'surname' => 'Mazza',
            ]),
            'adminData' => (object) [
                'street' => 'Admin street',
                'house_number' => '2',
                'postal_code' => '00100',
                'city' => 'Rome',
                'province' => 'RM',
                'vat_number' => '12345678901',
                'tax_code' => 'MZZDMN80A01H501U',
            ],
            'orderItems' => [[
                'description' => 'Private lesson',
                'price' => 20,
                'quantity' => 1,
                'total' => 20,
            ]],
            'total' => 20,
            'invoiceDate' => '16/06/2026',
            'invoiceNumber' => 1,
            'note' => '',
        ])->render();

        $this->assertStringContainsString('<title>Invoice</title>', $html);
        $this->assertStringContainsString('Customer', $html);
        $this->assertStringContainsString('TAXABLE AMOUNT', $html);
        $this->assertStringContainsString('Stamp duty EUR 2.00', $html);
    }
}

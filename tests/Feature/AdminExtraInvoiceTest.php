<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminExtraInvoiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_extra_invoice_stores_structured_metadata(): void
    {
        Storage::fake('private');
        $admin = $this->createAdmin();

        $this->actingAs($admin)
            ->post(route('admin.invoices.extra.store'), [
                'first_name' => 'Mario',
                'last_name' => 'Rossi',
                'street' => 'Via Roma',
                'house_number' => '1',
                'city' => 'Roma',
                'province' => 'RM',
                'postal_code' => '00100',
                'tax_code' => 'RSSMRA80A01H501U',
                'description' => 'Lezione privata',
                'price' => '25.50',
                'quantity' => '2',
                'note' => 'Nota test',
            ])
            ->assertRedirect(route('admin.invoices.created'));

        $invoice = Invoice::firstOrFail();

        $this->assertNull($invoice->order_id);
        $this->assertSame('extra', $invoice->source);
        $this->assertSame(5100, $invoice->total_amount);
        $this->assertSame('eur', $invoice->currency);
        $this->assertSame('Mario', $invoice->customer_snapshot['name']);
        $this->assertSame('Lezione privata', $invoice->line_items[0]['description']);
        $this->assertSame(2550, $invoice->line_items[0]['unit_price']);
        $this->assertSame('Nota test', $invoice->note);
        Storage::disk('private')->assertExists($invoice->file_path);
    }

    private function createAdmin(): User
    {
        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        Admin::create([
            'user_id' => $user->id,
            'street' => 'Via Admin',
            'house_number' => '1',
            'city' => 'Roma',
            'province' => 'RM',
            'postal_code' => '00100',
            'tax_code' => 'ADMADM80A01H501U',
            'vat_number' => 'IT12345678901',
        ]);

        return $user;
    }
}

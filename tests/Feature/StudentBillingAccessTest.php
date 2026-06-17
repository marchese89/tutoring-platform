<?php

namespace Tests\Feature;

use App\Enums\InvoiceSource;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentBillingAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_invoice_list_uses_order_invoices(): void
    {
        $student = Student::factory()->create();
        $otherStudent = Student::factory()->create();

        $ownOrder = Order::factory()->for($student)->create();
        $otherOrder = Order::factory()->for($otherStudent)->create();

        $ownInvoice = Invoice::factory()->create([
            'number' => 101,
            'issued_at' => now(),
            'order_id' => $ownOrder->id,
            'student_id' => $student->id,
            'source' => InvoiceSource::ORDER->value,
            'file_path' => 'invoices/2026/invoice_101.pdf',
        ]);

        $otherInvoice = Invoice::factory()->create([
            'number' => 999,
            'issued_at' => now(),
            'order_id' => $otherOrder->id,
            'student_id' => $otherStudent->id,
            'source' => InvoiceSource::ORDER->value,
            'file_path' => 'invoices/2026/invoice_999.pdf',
        ]);

        $this->actingAs($student->user)
            ->get(route('student.invoices.index'))
            ->assertOk()
            ->assertSee('101')
            ->assertSee('#'.$ownOrder->id)
            ->assertDontSee('999')
            ->assertSee(route('student.invoices.show', $ownInvoice->id), false)
            ->assertDontSee(route('student.invoices.show', $otherInvoice->id), false);
    }

    public function test_student_cannot_open_another_students_order_invoice(): void
    {
        $student = Student::factory()->create();
        $otherStudent = Student::factory()->create();
        $otherOrder = Order::factory()->for($otherStudent)->create();

        $invoice = Invoice::factory()->create([
            'number' => 999,
            'issued_at' => now(),
            'order_id' => $otherOrder->id,
            'student_id' => $otherStudent->id,
            'source' => InvoiceSource::ORDER->value,
            'file_path' => 'invoices/2026/invoice_999.pdf',
        ]);

        $this->actingAs($student->user)
            ->get(route('student.invoices.show', $invoice->id))
            ->assertNotFound();
    }

    public function test_student_can_open_own_direct_invoice(): void
    {
        $student = Student::factory()->create();

        $invoice = Invoice::factory()->create([
            'number' => 303,
            'issued_at' => now(),
            'student_id' => $student->id,
            'file_path' => 'extra-invoices/2026/invoice_303.pdf',
        ]);

        $this->actingAs($student->user)
            ->get(route('student.invoices.show', $invoice->id))
            ->assertOk()
            ->assertSee('Fattura pagamento extra')
            ->assertSee('src="/protected-files/extra-invoices/2026/invoice_303.pdf#view=FitH"', false)
            ->assertSee('class="pdf-viewer"', false);
    }

    public function test_student_cannot_open_another_students_direct_invoice(): void
    {
        $student = Student::factory()->create();
        $otherStudent = Student::factory()->create();

        $invoice = Invoice::factory()->create([
            'number' => 404,
            'issued_at' => now(),
            'student_id' => $otherStudent->id,
            'file_path' => 'extra-invoices/2026/invoice_404.pdf',
        ]);

        $this->actingAs($student->user)
            ->get(route('student.invoices.show', $invoice->id))
            ->assertNotFound();
    }
}

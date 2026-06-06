<?php

namespace Tests\Feature;

use App\Models\Invoice;
use App\Models\Order;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentInvoiceAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_invoice_list_uses_order_invoices(): void
    {
        $student = $this->createStudent();
        $otherStudent = $this->createStudent();

        $ownOrder = $this->createOrder($student);
        $otherOrder = $this->createOrder($otherStudent);

        $ownInvoice = Invoice::create([
            'number' => 101,
            'issued_at' => now(),
            'order_id' => $ownOrder->id,
            'file_path' => 'invoices/2026/invoice_101.pdf',
        ]);

        $otherInvoice = Invoice::create([
            'number' => 999,
            'issued_at' => now(),
            'order_id' => $otherOrder->id,
            'file_path' => 'invoices/2026/invoice_999.pdf',
        ]);

        $this->actingAs($student->user)
            ->get(route('student.invoices.index'))
            ->assertOk()
            ->assertSee('101')
            ->assertSee('#' . $ownOrder->id)
            ->assertDontSee('999')
            ->assertSee(route('student.invoices.show', $ownInvoice->id), false)
            ->assertDontSee(route('student.invoices.show', $otherInvoice->id), false);
    }

    public function test_student_cannot_open_another_students_order_invoice(): void
    {
        $student = $this->createStudent();
        $otherStudent = $this->createStudent();
        $otherOrder = $this->createOrder($otherStudent);

        $invoice = Invoice::create([
            'number' => 999,
            'issued_at' => now(),
            'order_id' => $otherOrder->id,
            'file_path' => 'invoices/2026/invoice_999.pdf',
        ]);

        $this->actingAs($student->user)
            ->get(route('student.invoices.show', $invoice->id))
            ->assertNotFound();
    }

    public function test_student_can_open_own_direct_invoice(): void
    {
        $student = $this->createStudent();

        $invoice = Invoice::create([
            'number' => 303,
            'issued_at' => now(),
            'student_id' => $student->id,
            'file_path' => 'extra-invoices/2026/invoice_303.pdf',
        ]);

        $this->actingAs($student->user)
            ->get(route('student.invoices.show', $invoice->id))
            ->assertOk()
            ->assertSee('Fattura pagamento extra');
    }

    public function test_student_cannot_open_another_students_direct_invoice(): void
    {
        $student = $this->createStudent();
        $otherStudent = $this->createStudent();

        $invoice = Invoice::create([
            'number' => 404,
            'issued_at' => now(),
            'student_id' => $otherStudent->id,
            'file_path' => 'extra-invoices/2026/invoice_404.pdf',
        ]);

        $this->actingAs($student->user)
            ->get(route('student.invoices.show', $invoice->id))
            ->assertNotFound();
    }

    private function createStudent(): Student
    {
        $user = User::factory()->create(['role' => 'student']);

        return Student::create([
            'user_id' => $user->id,
            'street' => 'Test street',
            'house_number' => '1',
            'city' => 'Rome',
            'province' => 'RM',
            'postal_code' => '00100',
            'tax_code' => strtoupper(fake()->unique()->bothify('????????????????')),
        ]);
    }

    private function createOrder(Student $student): Order
    {
        return Order::create([
            'student_id' => $student->id,
            'ordered_at' => now(),
        ]);
    }
}

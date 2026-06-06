<?php

namespace App\Services;

use App\Models\Admin;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\PaymentTransaction;
use App\Models\User;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class InvoiceService
{
    public function __construct(private readonly InvoiceNumberService $numbers) {}

    public function generatePdf(int $orderId, ?int $paymentTransactionId = null): Invoice
    {
        $existingInvoice = Invoice::where('order_id', $orderId)->first();

        if ($existingInvoice) {
            if ($paymentTransactionId && ! $existingInvoice->payment_transaction_id) {
                $existingInvoice->update(['payment_transaction_id' => $paymentTransactionId]);
            }

            return $existingInvoice;
        }

        $order = Order::findOrFail($orderId);
        $admin = User::where('role', 'admin')->first();
        $adminData = Admin::where('user_id', $admin->id)->first();
        $student = $order->student;
        $user = $student->user;
        $orderItems = $order->orderItems()->get();
        $total = $order->orderItems()->sum('price');

        $number = $this->numbers->next($order->created_at->year);

        $data = $order->created_at;

        $html = view('invoices.invoice', [
            'user' => $user,
            'customer' => $student,
            'admin' => $admin,
            'adminData' => $adminData,
            'orderItems' => $orderItems,
            'total' => $total,
            'order' => $order,
            'invoiceDate' => $data->format('d/m/Y'),
            'invoiceNumber' => $number,
        ])->render();

        $dompdf = new Dompdf;
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $pdf = $dompdf->output();

        $relativePath = "invoices/{$data->year}/invoice_{$number}.pdf";

        Storage::disk('private')->put(
            $relativePath,
            $pdf
        );

        return Invoice::create([
            'number' => $number,
            'issued_at' => $data,
            'order_id' => $order->id,
            'student_id' => $student->id,
            'payment_transaction_id' => $paymentTransactionId,
            'file_path' => $relativePath,
        ]);
    }

    public function generateExtraPaymentPdf(User $user, PaymentTransaction $transaction): Invoice
    {
        $existingInvoice = Invoice::where('payment_transaction_id', $transaction->id)->first();

        if ($existingInvoice) {
            return $existingInvoice;
        }

        $student = $user->student;

        if (! $student) {
            throw new RuntimeException('Extra payment invoices require a student user.');
        }

        $context = $transaction->context ?? [];
        $description = (string) ($context['description'] ?? '');
        $unitPrice = (float) ($context['unit_price'] ?? 0);
        $quantity = (int) ($context['quantity'] ?? 0);

        if ($description === '' || $unitPrice <= 0 || $quantity < 1) {
            throw new RuntimeException('Extra payment invoice context is incomplete.');
        }

        $admin = User::where('role', 'admin')->firstOrFail();
        $adminData = Admin::where('user_id', $admin->id)->firstOrFail();
        $issuedAt = $transaction->completed_at ?? now();
        $number = $this->numbers->next($issuedAt->year);
        $total = $transaction->amount / 100;

        $html = view('invoices.invoice', [
            'user' => $user,
            'customer' => $student,
            'admin' => $admin,
            'adminData' => $adminData,
            'orderItems' => [[
                'description' => $description,
                'price' => $unitPrice,
                'quantity' => $quantity,
                'total' => $total,
            ]],
            'total' => $total,
            'invoiceDate' => $issuedAt->format('d/m/Y'),
            'invoiceNumber' => $number,
            'note' => '',
        ])->render();

        $dompdf = new Dompdf;
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $relativePath = "extra-invoices/{$issuedAt->year}/invoice_{$number}.pdf";

        Storage::disk('private')->put($relativePath, $dompdf->output());

        return Invoice::create([
            'number' => $number,
            'issued_at' => $issuedAt,
            'student_id' => $student->id,
            'payment_transaction_id' => $transaction->id,
            'file_path' => $relativePath,
        ]);
    }
}

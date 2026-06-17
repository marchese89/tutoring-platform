<?php

namespace App\Services;

use App\Enums\InvoiceSource;
use App\Enums\UserRole;
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
        $admin = User::where('role', UserRole::ADMIN->value)->first();
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
            'source' => InvoiceSource::ORDER->value,
            'total_amount' => (int) round($total * 100),
            'currency' => 'eur',
            'customer_snapshot' => $this->customerSnapshot($user, $student),
            'line_items' => $orderItems->map(fn ($item) => [
                'description' => $item->description,
                'unit_price' => (int) round($item->price * 100),
                'quantity' => 1,
                'total' => (int) round($item->price * 100),
            ])->values()->all(),
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

        $admin = User::where('role', UserRole::ADMIN->value)->firstOrFail();
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
            'source' => InvoiceSource::EXTRA->value,
            'total_amount' => $transaction->amount,
            'currency' => $transaction->currency,
            'customer_snapshot' => $this->customerSnapshot($user, $student),
            'line_items' => [[
                'description' => $description,
                'unit_price' => (int) round($unitPrice * 100),
                'quantity' => $quantity,
                'total' => $transaction->amount,
            ]],
            'note' => '',
            'file_path' => $relativePath,
        ]);
    }

    public function generateManualExtraPdf(array $data): Invoice
    {
        $admin = User::where('role', UserRole::ADMIN->value)->firstOrFail();
        $adminData = Admin::where('user_id', $admin->id)->firstOrFail();
        $issuedAt = now();
        $number = $this->numbers->next($issuedAt->year);
        $price = (float) $data['price'];
        $quantity = (float) $data['quantity'];
        $total = $price * $quantity;

        $html = view('invoices.invoice', [
            'user' => (object) [
                'name' => $data['first_name'],
                'surname' => $data['last_name'],
            ],
            'customer' => (object) [
                'street' => $data['street'],
                'house_number' => $data['house_number'],
                'postal_code' => $data['postal_code'],
                'city' => $data['city'],
                'province' => $data['province'],
                'tax_code' => $data['tax_code'],
            ],
            'admin' => $admin,
            'adminData' => $adminData,
            'orderItems' => [[
                'description' => $data['description'],
                'price' => $price,
                'quantity' => $quantity,
                'total' => $total,
            ]],
            'total' => $total,
            'invoiceDate' => $issuedAt->format('d/m/Y'),
            'invoiceNumber' => $number,
            'note' => $data['note'] ?? '',
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
            'order_id' => null,
            'source' => InvoiceSource::EXTRA->value,
            'total_amount' => (int) round($total * 100),
            'currency' => 'eur',
            'customer_snapshot' => [
                'name' => $data['first_name'],
                'surname' => $data['last_name'],
                'street' => $data['street'],
                'house_number' => $data['house_number'],
                'postal_code' => $data['postal_code'],
                'city' => $data['city'],
                'province' => $data['province'],
                'tax_code' => $data['tax_code'],
            ],
            'line_items' => [[
                'description' => $data['description'],
                'unit_price' => (int) round($price * 100),
                'quantity' => $quantity,
                'total' => (int) round($total * 100),
            ]],
            'note' => $data['note'] ?? null,
            'file_path' => $relativePath,
        ]);
    }

    private function customerSnapshot(User $user, object $customer): array
    {
        return [
            'name' => $user->name,
            'surname' => $user->surname,
            'street' => $customer->street,
            'house_number' => $customer->house_number,
            'postal_code' => $customer->postal_code,
            'city' => $customer->city,
            'province' => $customer->province,
            'tax_code' => $customer->tax_code,
        ];
    }
}

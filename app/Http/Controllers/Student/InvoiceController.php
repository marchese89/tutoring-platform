<?php

namespace App\Http\Controllers\Student;

use App\Helpers\DateHelper;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    public function index(Request $request): View
    {
        $studentId = $request->user()->student->id;

        $invoices = Invoice::query()
            ->where(function ($query) use ($studentId) {
                $query->where('student_id', $studentId)
                    ->orWhereHas('order', fn ($order) => $order->where('student_id', $studentId));
            })
            ->orderByDesc('issued_at')
            ->orderByDesc('id')
            ->paginate(10)
            ->through(fn (Invoice $invoice) => [
                'id' => $invoice->id,
                'number' => $invoice->number,
                'order_id' => $invoice->order_id,
                'date' => $invoice->issued_at ? DateHelper::format($invoice->issued_at) : '-',
                'show_url' => route('student.invoices.show', $invoice->id),
            ]);

        return view('student.invoices', compact('invoices'));
    }

    public function show(Request $request, int $id): View
    {
        $studentId = $request->user()->student->id;

        $invoice = Invoice::query()
            ->where('id', $id)
            ->where(function ($query) use ($studentId) {
                $query->where('student_id', $studentId)
                    ->orWhereHas('order', fn ($order) => $order->where('student_id', $studentId));
            })
            ->firstOrFail();

        return view('student.invoice', [
            'invoice' => $invoice,
            'title' => $invoice->order_id
                ? __('student.invoices.order_title', ['number' => $invoice->order_id])
                : __('student.invoices.extra_payment_title'),
            'backUrl' => $invoice->order_id
                ? route('student.orders.show', $invoice->order_id)
                : route('student.invoices.index'),
        ]);
    }
}

<?php

namespace App\Http\Controllers\Student;

use App\Helpers\DateHelper;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    public function index(Request $request): View
    {
        $studentId = $request->user()->student->id;

        $invoices = Invoice::query()
            ->whereHas('order', fn ($query) => $query->where('student_id', $studentId))
            ->orderByDesc('issued_at')
            ->orderByDesc('id')
            ->get()
            ->map(fn (Invoice $invoice) => [
                'id' => $invoice->id,
                'number' => $invoice->number,
                'order_id' => $invoice->order_id,
                'date' => $invoice->issued_at ? DateHelper::format($invoice->issued_at) : '-',
            ]);

        return view('student.invoices', compact('invoices'));
    }

    public function showOrderInvoice(Request $request, int $id): View
    {
        Order::where('student_id', $request->user()->student->id)->findOrFail($id);

        $invoice = Invoice::where('order_id', $id)->firstOrFail();

        return view('student.invoice', compact('id', 'invoice'));
    }
}

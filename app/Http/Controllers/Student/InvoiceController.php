<?php

namespace App\Http\Controllers\Student;

use App\Helpers\DateHelper;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceSheet;
use App\Models\Order;
use App\Models\StudentInvoice;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    public function index(Request $request): View
    {
        $invoices = StudentInvoice::with('invoiceSheet')
            ->where('student_id', $request->user()->student->id)
            ->orderByDesc('created_at')
            ->get()
            ->map(fn(StudentInvoice $invoice) => [
                'id' => $invoice->id,
                'invoice_sheet_id' => $invoice->invoice_sheet_id,
                'date' => $invoice->invoiceSheet?->issued_at ? DateHelper::format($invoice->invoiceSheet->issued_at) : '-',
            ]);

        return view('student.invoices', compact('invoices'));
    }

    public function showOrderInvoice(Request $request, int $id): View
    {
        Order::where('student_id', $request->user()->student->id)->findOrFail($id);

        $invoice = Invoice::where('order_id', $id)->firstOrFail();

        return view('student.invoice', compact('id', 'invoice'));
    }

    public function showInvoiceSheet(Request $request, int $id): View
    {
        StudentInvoice::where('student_id', $request->user()->student->id)
            ->where('invoice_sheet_id', $id)
            ->firstOrFail();

        $invoiceSheet = InvoiceSheet::findOrFail($id);

        return view('student.invoice-sheet', compact('invoiceSheet'));
    }
}

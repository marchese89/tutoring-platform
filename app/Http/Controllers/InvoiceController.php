<?php

namespace App\Http\Controllers;

use App\Helpers\DateHelper;
use App\Models\Invoice;

class InvoiceController extends Controller
{
    public function showAll()
    {
        $invoices = Invoice::query()
            ->orderByDesc('issued_at')
            ->orderByDesc('id')
            ->paginate(10)
            ->through(fn (Invoice $invoice) => (object) [
                'number' => $invoice->number ?? '-',
                'date' => $invoice->issued_at ? DateHelper::format($invoice->issued_at) : '-',
                'showUrl' => $invoice->number ? route('admin.invoices.show', $invoice->number) : null,
            ]);

        return view('admin.billing.invoices', compact('invoices'));
    }

    public function show(Invoice $invoice)
    {
        return view('admin.billing.invoice-details', compact('invoice'));
    }
}

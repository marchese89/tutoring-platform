<?php

namespace App\Http\Controllers;

use App\Helpers\DateHelper;
use App\Models\Invoice;


class InvoiceController extends Controller
{
    public function showAll()
    {
        $fatture = Invoice::query()
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->get()
            ->map(fn (Invoice $invoice) => (object) [
                'number' => $invoice->number ?? '-',
                'date' => $invoice->date ? DateHelper::format($invoice->date) : '-',
                'showUrl' => $invoice->number ? route('admin.invoices.show', $invoice->number) : null,
            ]);

        return view('admin.billing.invoices', compact('fatture'));
    }

    public function show($number)
    {
        $fattura = Invoice::where('number', $number)->firstOrFail();

        return view('admin.billing.invoice-details', compact('fattura'));
    }
}

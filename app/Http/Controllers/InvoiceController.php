<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;


class InvoiceController extends Controller
{
    public function showAll()
    {
        $fatture = Invoice::all();

        return view('admin.billing.invoices', compact('fatture'));
    }

    public function show($number)
    {
        $fattura = Invoice::where('number', $number)->firstOrFail();

        return view('admin.billing.invoice-details', compact('fattura'));
    }
}

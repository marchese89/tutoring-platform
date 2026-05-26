<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;


class InvoiceController extends Controller
{
    public function showAll()
    {
        $fatture = Invoice::all();

        return view('admin.billing.fatture', compact('fatture'));
    }

    public function show($number)
    {
        $fattura = Invoice::where('number', $number)->firstOrFail();

        return view('admin.visualizza-fattura', compact('fattura'));
    }
}

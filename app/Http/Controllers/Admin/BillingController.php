<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Invoice;
use App\Helpers\DateHelper;


class BillingController extends Controller
{
    public function showOrder(int $id)
    {
        $ordine = Order::where('id', '=', $id)->first();
        $prodotti = OrderProduct::where('id_ordine', '=', request('id'))->get();
        $tot_ordine = OrderProduct::where('id_ordine', '=', request('id'))->sum('price');
        return View('admin.billing.ordine', compact('ordine', 'prodotti', 'tot_ordine'));
    }

    public function showInvoice(int $id)
    {
        $invoice = Invoice::where('order_id', '=', $id)->first();
        return view('admin.billing.fattura', compact('invoice'));
    }

    public function vendite()
    {
        $primoOrdine = Order::orderBy('date', 'desc')->first();

        if (!$primoOrdine) {
            return view('admin.billing.vendite', [
                'hasOrders' => false
            ]);
        }

        $data = DateHelper::parse($primoOrdine->date);

        $years = Order::selectRaw('YEAR(date) as year')
            ->groupBy('year')
            ->orderBy('year')
            ->get();

        $months = Order::selectRaw('MONTH(date) as month')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('admin.billing.vendite', [
            'hasOrders' => true,
            'dataPrimo' => $data,
            'years' => $years,
            'months' => $months
        ]);
    }

    public function cambiaTabellaOrdini(Request $request)
    {
        $anno = $request->anno;
        $mese = $request->mese;

        $orders = Order::whereYear('date', $anno)
            ->whereMonth('date', $mese)
            ->orderBy('date', 'desc')
            ->get();

        $totale = 0;

        $ordini = $orders->map(function ($order) use (&$totale) {

            $totale += $order->total;

            return [
                'id' => $order->id,
                'studente' => $order->student->user->name,
                'data' => $order->date,
                'totale' => $order->total
            ];
        });

        return response()->json([
            'ordini' => $ordini,
            'totale' => $totale
        ]);
    }
}

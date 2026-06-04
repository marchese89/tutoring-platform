<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Invoice;
use App\Helpers\DateHelper;


class BillingController extends Controller
{
    public function showOrder(int $id)
    {
        $ordine = Order::where('id', '=', $id)->first();
        $prodotti = OrderItem::where('order_id', '=', request('id'))->get();
        $tot_ordine = OrderItem::where('order_id', '=', request('id'))->sum('price');
        return View('admin.billing.order', compact('ordine', 'prodotti', 'tot_ordine'));
    }

    public function showInvoice(int $id)
    {
        $invoice = Invoice::where('order_id', '=', $id)->first();
        return view('admin.billing.invoice', compact('invoice'));
    }

    public function sales()
    {
        $primoOrdine = Order::orderByDesc('ordered_at')->first();

        if (!$primoOrdine) {
            return view('admin.billing.sales', [
                'hasOrders' => false
            ]);
        }

        $data = DateHelper::parse($primoOrdine->ordered_at);

        $years = Order::selectRaw('YEAR(ordered_at) as year')
            ->groupBy('year')
            ->orderBy('year')
            ->get();

        $months = Order::selectRaw('MONTH(ordered_at) as month')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('admin.billing.sales', [
            'hasOrders' => true,
            'dataPrimo' => $data,
            'years' => $years,
            'months' => $months
        ]);
    }

    public function ordersTable(Request $request)
    {
        $anno = $request->anno;
        $mese = $request->mese;

        $orders = Order::with(['student.user', 'orderItems'])
            ->whereYear('ordered_at', $anno)
            ->whereMonth('ordered_at', $mese)
            ->orderByDesc('ordered_at')
            ->get();

        $totale = 0;

        $ordini = $orders->map(function ($order) use (&$totale) {

            $orderTotal = $order->orderItems->sum('price');
            $totale += $orderTotal;

            return [
                'id' => $order->id,
                'studente' => $order->student->user->name . ' ' . $order->student->user->surname,
                'data' => DateHelper::format($order->ordered_at),
                'totale' => $orderTotal
            ];
        });

        return response()->json([
            'ordini' => $ordini,
            'totale' => $totale
        ]);
    }
}

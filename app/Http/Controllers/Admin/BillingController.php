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
        $order = Order::where('id', '=', $id)->first();
        $products = OrderItem::where('order_id', '=', request('id'))->get();
        $orderTotal = OrderItem::where('order_id', '=', request('id'))->sum('price');
        return View('admin.billing.order', compact('order', 'products', 'orderTotal'));
    }

    public function showInvoice(int $id)
    {
        $invoice = Invoice::where('order_id', '=', $id)->first();
        return view('admin.billing.invoice', compact('invoice'));
    }

    public function sales()
    {
        $firstOrder = Order::orderByDesc('ordered_at')->first();

        if (!$firstOrder) {
            return view('admin.billing.sales', [
                'hasOrders' => false
            ]);
        }

        $firstOrderDate = DateHelper::parse($firstOrder->ordered_at);

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
            'firstOrderDate' => $firstOrderDate,
            'years' => $years,
            'months' => $months
        ]);
    }

    public function ordersTable(Request $request)
    {
        $year = $request->year;
        $month = $request->month;

        $orders = Order::with(['student.user', 'orderItems'])
            ->whereYear('ordered_at', $year)
            ->whereMonth('ordered_at', $month)
            ->orderByDesc('ordered_at')
            ->get();

        $total = 0;

        $mappedOrders = $orders->map(function ($order) use (&$total) {

            $orderTotal = $order->orderItems->sum('price');
            $total += $orderTotal;

            return [
                'id' => $order->id,
                'student' => $order->student->user->name . ' ' . $order->student->user->surname,
                'date' => DateHelper::format($order->ordered_at),
                'total' => $orderTotal
            ];
        });

        return response()->json([
            'orders' => $mappedOrders,
            'total' => $total
        ]);
    }
}

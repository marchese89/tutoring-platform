<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\DateHelper;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Order;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    public function showOrder(int $id)
    {
        $order = Order::with('orderItems')->findOrFail($id);
        $products = $order->orderItems->map(fn ($item) => [
            'product_id' => $item->product_id,
            'product_type_label' => $this->productTypeLabel((int) $item->product_type),
            'product_type_class' => $this->productTypeBadgeClass((int) $item->product_type),
            'price' => $item->price,
        ]);

        return view('admin.billing.order', [
            'order' => $order,
            'orderDate' => DateHelper::format($order->ordered_at),
            'products' => $products,
            'orderTotal' => $order->orderItems->sum('price'),
        ]);
    }

    public function showInvoice(int $id)
    {
        $invoice = Invoice::where('order_id', $id)->firstOrFail();

        return view('admin.billing.invoice', [
            'invoice' => $invoice,
            'orderId' => $invoice->order_id,
        ]);
    }

    public function sales()
    {
        $firstOrder = Order::orderByDesc('ordered_at')->first();

        if (! $firstOrder) {
            return view('admin.billing.sales', [
                'hasOrders' => false,
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
            ->pluck('month')
            ->map(fn ($month) => [
                'value' => $month,
                'label' => DateHelper::monthName((int) $month),
            ]);

        return view('admin.billing.sales', [
            'hasOrders' => true,
            'firstOrderDate' => $firstOrderDate,
            'firstOrderMonthLabel' => DateHelper::monthName((int) $firstOrderDate['month']),
            'years' => $years,
            'months' => $months,
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
                'student' => $order->student->user->name.' '.$order->student->user->surname,
                'date' => DateHelper::format($order->ordered_at),
                'total' => $orderTotal,
            ];
        });

        return response()->json([
            'orders' => $mappedOrders,
            'total' => $total,
        ]);
    }

    private function productTypeLabel(int $type): string
    {
        return match ($type) {
            0 => 'Lezione',
            2 => 'Esercizio',
            5 => 'Lezione su richiesta',
            default => 'Prodotto',
        };
    }

    private function productTypeBadgeClass(int $type): string
    {
        return match ($type) {
            0 => 'bg-primary-subtle text-primary',
            2 => 'bg-success-subtle text-success',
            5 => 'bg-warning-subtle text-dark',
            default => 'bg-secondary-subtle text-secondary',
        };
    }
}

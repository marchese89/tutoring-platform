<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ProductType;
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
            'id' => $item->product_id,
            'type_label' => $this->productTypeLabel((int) $item->product_type),
            'type_class' => $this->productTypeBadgeClass((int) $item->product_type),
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

        $orderedDates = Order::query()
            ->whereNotNull('ordered_at')
            ->pluck('ordered_at');

        $years = $orderedDates
            ->map(fn ($date) => DateHelper::parse($date)['year'])
            ->unique()
            ->sort()
            ->values();

        $months = $orderedDates
            ->map(fn ($date) => DateHelper::parse($date)['month'])
            ->unique()
            ->sort()
            ->values()
            ->map(fn ($month) => [
                'value' => $month,
                'label' => DateHelper::monthName((int) $month),
            ]);

        return view('admin.billing.sales', [
            'hasOrders' => true,
            'selectedYear' => $firstOrderDate['year'],
            'selectedMonth' => $firstOrderDate['month'],
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
            ProductType::LESSON->value => __('ui.product_types.lesson'),
            ProductType::EXERCISE->value => __('ui.product_types.exercise'),
            ProductType::REQUESTED_LESSON->value => __('ui.product_types.requested_lesson'),
            default => __('ui.product_types.product'),
        };
    }

    private function productTypeBadgeClass(int $type): string
    {
        return match ($type) {
            ProductType::LESSON->value => 'bg-primary-subtle text-primary',
            ProductType::EXERCISE->value => 'bg-success-subtle text-success',
            ProductType::REQUESTED_LESSON->value => 'bg-warning-subtle text-dark',
            default => 'bg-secondary-subtle text-secondary',
        };
    }
}

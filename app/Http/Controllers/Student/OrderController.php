<?php

namespace App\Http\Controllers\Student;

use App\Enums\ProductType;
use App\Helpers\DateHelper;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $student = $request->user()->student;
        $firstOrder = Order::where('student_id', $student->id)
            ->orderByDesc('ordered_at')
            ->first();

        if (! $firstOrder) {
            return view('student.orders', [
                'hasOrders' => false,
            ]);
        }

        $firstOrderDate = DateHelper::parse($firstOrder->ordered_at);

        $orderedDates = Order::query()
            ->where('student_id', $student->id)
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

        return view('student.orders', [
            'hasOrders' => true,
            'selectedYear' => $firstOrderDate['year'],
            'selectedMonth' => $firstOrderDate['month'],
            'selectedMonthLabel' => DateHelper::monthName((int) $firstOrderDate['month']),
            'years' => $years,
            'months' => $months,
        ]);
    }

    public function table(Request $request): JsonResponse
    {
        $student = $request->user()->student;
        $orders = Order::with('orderItems')
            ->where('student_id', $student->id)
            ->whereYear('ordered_at', $request->input('year'))
            ->whereMonth('ordered_at', $request->input('month'))
            ->orderByDesc('ordered_at')
            ->get();

        $total = 0;
        $mappedOrders = $orders->map(function (Order $order) use (&$total) {
            $orderTotal = $order->orderItems->sum('price');
            $total += $orderTotal;

            return [
                'id' => $order->id,
                'date' => DateHelper::format($order->ordered_at),
                'total' => $orderTotal,
            ];
        });

        return response()->json([
            'orders' => $mappedOrders,
            'total' => $total,
        ]);
    }

    public function show(Request $request, int $id): View
    {
        $order = Order::with(['invoice', 'orderItems'])
            ->where('student_id', $request->user()->student->id)
            ->findOrFail($id);
        $products = $order->orderItems->map(fn ($product) => [
            'id' => $product->product_id,
            'type_label' => $this->productTypeLabel((int) $product->product_type),
            'type_class' => $this->productTypeBadgeClass((int) $product->product_type),
            'price' => $product->price,
        ]);

        return view('student.order', [
            'order' => $order,
            'orderDate' => DateHelper::format($order->ordered_at),
            'products' => $products,
            'orderTotal' => $order->orderItems->sum('price'),
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

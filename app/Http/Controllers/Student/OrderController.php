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

        $years = Order::where('student_id', $student->id)
            ->selectRaw('YEAR(ordered_at) as year')
            ->groupBy('year')
            ->orderBy('year')
            ->pluck('year');

        $months = Order::where('student_id', $student->id)
            ->selectRaw('MONTH(ordered_at) as month')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('month')
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
            'type' => $this->productTypeLabel((int) $product->product_type),
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
            ProductType::LESSON->value => 'lezione',
            ProductType::EXERCISE->value => 'esercizio',
            ProductType::REQUESTED_LESSON->value => 'lezione su richiesta',
            default => 'prodotto',
        };
    }
}

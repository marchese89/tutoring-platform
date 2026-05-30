<?php

namespace App\Http\Controllers\Student;

use App\Helpers\DateHelper;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\PurchaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $student = $request->user()->student;
        $firstOrder = Order::where('student_id', $student->id)
            ->orderByDesc('date')
            ->first();

        if (!$firstOrder) {
            return view('student.orders', [
                'hasOrders' => false,
            ]);
        }

        $firstOrderDate = DateHelper::parse($firstOrder->date);

        $years = Order::where('student_id', $student->id)
            ->selectRaw('YEAR(date) as year')
            ->groupBy('year')
            ->orderBy('year')
            ->pluck('year');

        $months = Order::where('student_id', $student->id)
            ->selectRaw('MONTH(date) as month')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('month')
            ->map(fn($month) => [
                'value' => $month,
                'label' => PurchaseService::stringa_mese((int) $month),
            ]);

        return view('student.orders', [
            'hasOrders' => true,
            'selectedYear' => $firstOrderDate['anno'],
            'selectedMonth' => $firstOrderDate['mese'],
            'selectedMonthLabel' => PurchaseService::stringa_mese((int) $firstOrderDate['mese']),
            'years' => $years,
            'months' => $months,
        ]);
    }

    public function table(Request $request): JsonResponse
    {
        $student = $request->user()->student;
        $orders = Order::with('order_products')
            ->where('student_id', $student->id)
            ->whereYear('date', $request->input('anno'))
            ->whereMonth('date', $request->input('mese'))
            ->orderByDesc('date')
            ->get();

        $total = 0;
        $mappedOrders = $orders->map(function (Order $order) use (&$total) {
            $orderTotal = $order->order_products->sum('price');
            $total += $orderTotal;

            return [
                'id' => $order->id,
                'data' => DateHelper::format($order->date),
                'totale' => $orderTotal,
            ];
        });

        return response()->json([
            'ordini' => $mappedOrders,
            'totale' => $total,
        ]);
    }

    public function show(Request $request, int $id): View
    {
        $order = Order::with('order_products')
            ->where('student_id', $request->user()->student->id)
            ->findOrFail($id);
        $products = $order->order_products->map(fn($product) => [
            'id' => $product->id_prodotto,
            'type' => $this->productTypeLabel((int) $product->tipo_prodotto),
            'price' => $product->price,
        ]);

        return view('student.order', [
            'ordine' => $order,
            'orderDate' => DateHelper::format($order->date),
            'prodotti' => $products,
            'tot_ordine' => $order->order_products->sum('price'),
        ]);
    }

    private function productTypeLabel(int $type): string
    {
        return match ($type) {
            0 => 'lezione',
            2 => 'esercizio',
            5 => 'lezione su richiesta',
            default => 'prodotto',
        };
    }
}

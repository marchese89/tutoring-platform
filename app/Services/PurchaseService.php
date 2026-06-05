<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;

class PurchaseService
{
    public static function isProductPurchased($studentId, $productId, $productType): bool
    {
        $orders = Order::where('student_id', '=', $studentId)->get();
        foreach ($orders as $order) {
            $orderItems = OrderItem::where('order_id', '=', $order->id)->get();
            foreach ($orderItems as $item) {
                if ($item->product_id == $productId && $item->product_type == $productType) {
                    return true;
                }
            }
        }

        return false;
    }

    public static function orderTotal($orderId): int
    {
        $total = 0;

        $orderItems = OrderItem::where('order_id', '=', $orderId)->get();
        foreach ($orderItems as $item) {
            $total += $item->price;
        }

        return $total;
    }

    public static function monthName($month)
    {
        switch ($month) {
            case 1:
                return 'Gennaio';
            case 2:
                return 'Febbraio';
            case 3:
                return 'Marzo';
            case 4:
                return 'Aprile';
            case 5:
                return 'Maggio';
            case 6:
                return 'Giugno';
            case 7:
                return 'Luglio';
            case 8:
                return 'Agosto';
            case 9:
                return 'Settembre';
            case 10:
                return 'Ottobre';
            case 11:
                return 'Novembre';
            case 12:
                return 'Dicembre';
        }
    }
}

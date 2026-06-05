<?php

namespace App\Services;

use App\Models\OrderItem;

class PurchaseService
{
    public static function isProductPurchased($studentId, $productId, $productType): bool
    {
        return OrderItem::query()
            ->where('product_id', $productId)
            ->where('product_type', $productType)
            ->whereHas('order', fn ($query) => $query->where('student_id', $studentId))
            ->exists();
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

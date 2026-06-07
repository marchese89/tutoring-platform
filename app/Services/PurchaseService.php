<?php

namespace App\Services;

use App\Models\OrderItem;
use Illuminate\Support\Collection;

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

    public static function purchasedProductIds(int $studentId, int $productType): Collection
    {
        return OrderItem::query()
            ->where('product_type', $productType)
            ->whereHas('order', fn ($query) => $query->where('student_id', $studentId))
            ->pluck('product_id');
    }

    public static function orderTotal($orderId): int
    {
        return OrderItem::where('order_id', $orderId)->sum('price');
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

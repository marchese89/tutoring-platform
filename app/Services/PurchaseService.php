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
}

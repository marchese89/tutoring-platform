<?php

namespace App\Services;

use App\Models\OrderItem;
use Illuminate\Support\Collection;

class PurchaseService
{
    public function isProductPurchased(int $studentId, int $productId, int $productType): bool
    {
        return OrderItem::query()
            ->where('product_id', $productId)
            ->where('product_type', $productType)
            ->whereHas('order', fn ($query) => $query->where('student_id', $studentId))
            ->exists();
    }

    public function purchasedProductIds(int $studentId, int $productType): Collection
    {
        return OrderItem::query()
            ->where('product_type', $productType)
            ->whereHas('order', fn ($query) => $query->where('student_id', $studentId))
            ->pluck('product_id');
    }
}

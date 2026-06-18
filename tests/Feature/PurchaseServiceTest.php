<?php

namespace Tests\Feature;

use App\Http\Utility\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Student;
use App\Services\PurchaseService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PurchaseServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_finds_only_products_purchased_by_the_selected_student_and_type(): void
    {
        $student = Student::factory()->create();
        $otherStudent = Student::factory()->create();

        $this->createOrderItem($student, 10, CartItem::LESSON);
        $this->createOrderItem($student, 20, CartItem::EXERCISE);
        $this->createOrderItem($otherStudent, 30, CartItem::LESSON);

        $purchases = app(PurchaseService::class);

        $this->assertTrue(
            $purchases->isProductPurchased($student->id, 10, CartItem::LESSON)
        );
        $this->assertFalse(
            $purchases->isProductPurchased($student->id, 20, CartItem::LESSON)
        );
        $this->assertFalse(
            $purchases->isProductPurchased($student->id, 30, CartItem::LESSON)
        );
        $this->assertSame(
            [10],
            $purchases->purchasedProductIds($student->id, CartItem::LESSON)->all()
        );
    }

    private function createOrderItem(Student $student, int $productId, int $productType): void
    {
        $order = Order::factory()
            ->for($student)
            ->create();

        OrderItem::factory()
            ->for($order)
            ->create([
                'product_id' => $productId,
                'product_type' => $productType,
            ]);
    }
}

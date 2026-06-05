<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class HttpMethodSafetyTest extends TestCase
{
    public function test_cart_mutation_only_accepts_post_requests(): void
    {
        $route = Route::getRoutes()->getByName('cart.items.store');

        $this->assertSame(['POST'], $route->methods());
    }

    public function test_logout_only_accepts_post_requests(): void
    {
        $route = Route::getRoutes()->getByName('logout');

        $this->assertSame(['POST'], $route->methods());
    }

    public function test_cart_payment_intent_only_accepts_post_requests(): void
    {
        $route = Route::getRoutes()->getByName('payment.process');

        $this->assertSame(['POST'], $route->methods());
    }

    public function test_extra_payment_intent_only_accepts_post_requests(): void
    {
        $route = Route::getRoutes()->getByName('payment.extra.intent');

        $this->assertSame(['POST'], $route->methods());
    }
}

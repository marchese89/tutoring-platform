<?php

namespace Tests\Feature;

use App\Models\InvoiceSequence;
use App\Services\InvoiceNumberService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoiceNumberServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_numbers_are_sequential_within_the_same_year(): void
    {
        $service = $this->app->make(InvoiceNumberService::class);

        $this->assertSame(1, $service->next(2026));
        $this->assertSame(2, $service->next(2026));
        $this->assertSame(2, InvoiceSequence::findOrFail(2026)->last_number);
    }

    public function test_each_year_has_an_independent_sequence(): void
    {
        $service = $this->app->make(InvoiceNumberService::class);

        $this->assertSame(1, $service->next(2026));
        $this->assertSame(1, $service->next(2027));
    }
}

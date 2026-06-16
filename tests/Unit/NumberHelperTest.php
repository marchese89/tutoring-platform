<?php

namespace Tests\Unit;

use App\Helpers\NumberHelper;
use PHPUnit\Framework\TestCase;

class NumberHelperTest extends TestCase
{
    public function test_it_formats_numbers_for_supported_locales_without_intl(): void
    {
        $this->assertSame('1.234,50', NumberHelper::format(1234.5, 2, 'it'));
        $this->assertSame('1,234.50', NumberHelper::format(1234.5, 2, 'en'));
    }
}

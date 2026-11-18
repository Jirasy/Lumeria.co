<?php

namespace Tests\Feature;

use App\Services\PaylaterCalculator;
use InvalidArgumentException;
use Tests\TestCase;

class PaylaterCalculatorTest extends TestCase
{
    public function test_flat_margin_is_principal_times_rate_times_months(): void
    {
        $r = (new PaylaterCalculator)->calculate(36000, 3, 2.0);

        $this->assertSame(2160, $r['margin']);
        $this->assertSame(38160, $r['total']);
        $this->assertSame(12720, $r['installment']);
    }

    public function test_installment_is_rounded_up_and_total_matches_installments(): void
    {
        $r = (new PaylaterCalculator)->calculate(10000, 3, 2.0);

        $this->assertSame(3534, $r['installment']);
        $this->assertSame(10602, $r['total']);
        $this->assertSame(602, $r['margin']);
        $this->assertSame($r['installment'] * 3, $r['total']);
    }

    public function test_one_month_tenor(): void
    {
        $r = (new PaylaterCalculator)->calculate(12000, 1, 2.0);

        $this->assertSame(240, $r['margin']);
        $this->assertSame(12240, $r['installment']);
        $this->assertSame(12240, $r['total']);
    }

    public function test_plans_cover_all_configured_tenors(): void
    {
        $plans = (new PaylaterCalculator)->plans(24000);

        $this->assertSame([1, 2, 3], array_column($plans, 'tenor'));
    }

    public function test_invalid_tenor_throws(): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new PaylaterCalculator)->calculate(10000, 0, 2.0);
    }
}
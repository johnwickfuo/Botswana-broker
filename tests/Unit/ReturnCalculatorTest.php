<?php

namespace Tests\Unit;

use App\Models\Plan;
use App\Services\ReturnCalculator;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * Pure unit tests for the ReturnCalculator — no database or app boot. Plan
 * models are instantiated in-memory and the four amount_type/return_type
 * combinations are each verified, plus the validation guards.
 */
class ReturnCalculatorTest extends TestCase
{
    private ReturnCalculator $calc;

    protected function setUp(): void
    {
        parent::setUp();
        $this->calc = new ReturnCalculator();
    }

    private function plan(array $attributes): Plan
    {
        return new Plan($attributes);
    }

    /** Combination 1: fixed amount + fixed return. */
    public function test_fixed_amount_with_fixed_return(): void
    {
        $plan = $this->plan([
            'amount_type'  => 'fixed',
            'fixed_amount' => 1000,
            'return_type'  => 'fixed',
            'fixed_return' => 200,
        ]);

        $result = $this->calc->calculate($plan);

        $this->assertSame(1000.00, $result['principal']);
        $this->assertSame(200.00, $result['return']);
        $this->assertSame(1200.00, $result['total']);
    }

    /** Combination 2: fixed amount + percentage return. */
    public function test_fixed_amount_with_percentage_return(): void
    {
        $plan = $this->plan([
            'amount_type'       => 'fixed',
            'fixed_amount'      => 1000,
            'return_type'       => 'percentage',
            'return_percentage' => 15,
        ]);

        $result = $this->calc->calculate($plan);

        $this->assertSame(1000.00, $result['principal']);
        $this->assertSame(150.00, $result['return']);
        $this->assertSame(1150.00, $result['total']);
    }

    /** Combination 3: ranged amount + fixed return (flat, independent of amount). */
    public function test_ranged_amount_with_fixed_return(): void
    {
        $plan = $this->plan([
            'amount_type'  => 'ranged',
            'min_amount'   => 500,
            'max_amount'   => 5000,
            'return_type'  => 'fixed',
            'fixed_return' => 300,
        ]);

        $result = $this->calc->calculate($plan, 2000);

        $this->assertSame(2000.00, $result['principal']);
        $this->assertSame(300.00, $result['return']);
        $this->assertSame(2300.00, $result['total']);
    }

    /** Combination 4: ranged amount + percentage return. */
    public function test_ranged_amount_with_percentage_return(): void
    {
        $plan = $this->plan([
            'amount_type'       => 'ranged',
            'min_amount'        => 500,
            'max_amount'        => 5000,
            'return_type'       => 'percentage',
            'return_percentage' => 10,
        ]);

        $result = $this->calc->calculate($plan, 2000);

        $this->assertSame(2000.00, $result['principal']);
        $this->assertSame(200.00, $result['return']);
        $this->assertSame(2200.00, $result['total']);
    }

    /** Legacy "fixed_amount" return_type alias is treated as a fixed return. */
    public function test_legacy_fixed_amount_return_type_alias(): void
    {
        $plan = $this->plan([
            'amount_type'  => 'fixed',
            'fixed_amount' => 800,
            'return_type'  => 'fixed_amount',
            'fixed_return' => 120,
        ]);

        $this->assertSame(120.00, $this->calc->calculate($plan)['return']);
    }

    public function test_ranged_requires_an_amount(): void
    {
        $plan = $this->plan([
            'amount_type'       => 'ranged',
            'min_amount'        => 500,
            'max_amount'        => 5000,
            'return_type'       => 'percentage',
            'return_percentage' => 10,
        ]);

        $this->expectException(InvalidArgumentException::class);
        $this->calc->calculate($plan); // no amount supplied
    }

    public function test_ranged_amount_below_minimum_is_rejected(): void
    {
        $plan = $this->plan([
            'amount_type'       => 'ranged',
            'min_amount'        => 500,
            'max_amount'        => 5000,
            'return_type'       => 'percentage',
            'return_percentage' => 10,
        ]);

        $this->expectException(InvalidArgumentException::class);
        $this->calc->calculate($plan, 100);
    }

    public function test_ranged_amount_above_maximum_is_rejected(): void
    {
        $plan = $this->plan([
            'amount_type'       => 'ranged',
            'min_amount'        => 500,
            'max_amount'        => 5000,
            'return_type'       => 'fixed',
            'fixed_return'      => 300,
        ]);

        $this->expectException(InvalidArgumentException::class);
        $this->calc->calculate($plan, 9000);
    }

    public function test_fixed_plan_rejects_mismatched_amount(): void
    {
        $plan = $this->plan([
            'amount_type'  => 'fixed',
            'fixed_amount' => 1000,
            'return_type'  => 'fixed',
            'fixed_return' => 200,
        ]);

        $this->expectException(InvalidArgumentException::class);
        $this->calc->calculate($plan, 1500);
    }
}

<?php

namespace App\Services;

use App\Models\Plan;
use InvalidArgumentException;

/**
 * Single source of truth for investment-return maths across all four plan
 * combinations:
 *
 *   amount_type   x   return_type
 *   -----------       -----------
 *   fixed             fixed        -> invest fixed_amount, gain fixed_return
 *   fixed             percentage   -> invest fixed_amount, gain principal * pct
 *   ranged            fixed        -> invest amount in [min,max], gain fixed_return
 *   ranged            percentage   -> invest amount in [min,max], gain principal * pct
 *
 * "return" is the profit (the gain); "total" is principal + return.
 */
class ReturnCalculator
{
    /**
     * Calculate the return breakdown for a plan and an (optional) invested
     * amount.
     *
     * @param  float|null  $amount  Required for ranged plans; for fixed plans
     *                              it must equal fixed_amount when supplied.
     * @return array{principal: float, return: float, total: float}
     *
     * @throws InvalidArgumentException
     */
    public function calculate(Plan $plan, ?float $amount = null): array
    {
        $principal = $this->resolvePrincipal($plan, $amount);
        $return    = $this->resolveReturn($plan, $principal);

        return [
            'principal' => round($principal, 2),
            'return'    => round($return, 2),
            'total'     => round($principal + $return, 2),
        ];
    }

    /**
     * Convenience accessor returning just the profit.
     */
    public function profit(Plan $plan, ?float $amount = null): float
    {
        return $this->calculate($plan, $amount)['return'];
    }

    /**
     * Determine the invested principal, validating against the plan's
     * amount_type constraints.
     */
    private function resolvePrincipal(Plan $plan, ?float $amount): float
    {
        $amountType = $this->normalizeAmountType($plan);

        if ($amountType === Plan::AMOUNT_FIXED) {
            $fixed = $this->toFloat($plan->fixed_amount);
            if ($fixed === null) {
                throw new InvalidArgumentException('A fixed-amount plan must define fixed_amount.');
            }
            // If a caller supplies an amount it must match the fixed amount.
            if ($amount !== null && round($amount, 2) !== round($fixed, 2)) {
                throw new InvalidArgumentException(
                    "This plan requires a fixed investment of {$fixed}."
                );
            }
            return $fixed;
        }

        // Ranged.
        $min = $this->toFloat($plan->min_amount);
        $max = $this->toFloat($plan->max_amount);
        if ($min === null || $max === null) {
            throw new InvalidArgumentException('A ranged plan must define min_amount and max_amount.');
        }
        if ($min > $max) {
            throw new InvalidArgumentException('min_amount cannot be greater than max_amount.');
        }
        if ($amount === null) {
            throw new InvalidArgumentException('An investment amount is required for a ranged plan.');
        }
        if ($amount < $min || $amount > $max) {
            throw new InvalidArgumentException(
                "Investment amount {$amount} is outside the allowed range {$min}–{$max}."
            );
        }

        return (float) $amount;
    }

    /**
     * Determine the profit for the resolved principal.
     */
    private function resolveReturn(Plan $plan, float $principal): float
    {
        if ($this->normalizeReturnType($plan) === Plan::RETURN_PERCENTAGE) {
            $pct = $this->toFloat($plan->return_percentage);
            if ($pct === null) {
                throw new InvalidArgumentException('A percentage-return plan must define return_percentage.');
            }
            return $principal * ($pct / 100);
        }

        // Fixed return — flat profit, independent of the principal.
        $fixedReturn = $this->toFloat($plan->fixed_return);
        if ($fixedReturn === null) {
            throw new InvalidArgumentException('A fixed-return plan must define fixed_return.');
        }
        return $fixedReturn;
    }

    private function normalizeAmountType(Plan $plan): string
    {
        $type = strtolower((string) $plan->amount_type);
        if (!in_array($type, [Plan::AMOUNT_FIXED, Plan::AMOUNT_RANGED], true)) {
            throw new InvalidArgumentException("Unknown amount_type: '{$plan->amount_type}'.");
        }
        return $type;
    }

    /**
     * Normalize the (shared/legacy) return_type column. Both "fixed" and the
     * legacy "fixed_amount" mean a flat fixed return.
     */
    private function normalizeReturnType(Plan $plan): string
    {
        $type = strtolower((string) $plan->return_type);
        if (in_array($type, ['fixed', 'fixed_amount'], true)) {
            return Plan::RETURN_FIXED;
        }
        if ($type === Plan::RETURN_PERCENTAGE) {
            return Plan::RETURN_PERCENTAGE;
        }
        throw new InvalidArgumentException("Unknown return_type: '{$plan->return_type}'.");
    }

    private function toFloat($value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }
        return (float) $value;
    }
}

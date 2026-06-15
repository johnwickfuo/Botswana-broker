<?php

namespace App\Services;

use App\Models\Asset;
use InvalidArgumentException;

/**
 * Single source of truth for investment-return maths. Investments are made
 * into ASSETS, each carrying its own terms:
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
     * Calculate the return breakdown for an asset and an (optional) invested
     * amount.
     *
     * @return array{principal: float, return: float, total: float}
     *
     * @throws InvalidArgumentException
     */
    public function calculate(Asset $asset, ?float $amount = null): array
    {
        $principal = $this->resolvePrincipal($asset, $amount);
        $return    = $this->resolveReturn($asset, $principal);

        return [
            'principal' => round($principal, 2),
            'return'    => round($return, 2),
            'total'     => round($principal + $return, 2),
        ];
    }

    public function profit(Asset $asset, ?float $amount = null): float
    {
        return $this->calculate($asset, $amount)['return'];
    }

    private function resolvePrincipal(Asset $asset, ?float $amount): float
    {
        if ($this->normalizeAmountType($asset) === Asset::AMOUNT_FIXED) {
            $fixed = $this->toFloat($asset->fixed_amount);
            if ($fixed === null) {
                throw new InvalidArgumentException('A fixed-amount asset must define fixed_amount.');
            }
            if ($amount !== null && round($amount, 2) !== round($fixed, 2)) {
                throw new InvalidArgumentException("This asset requires a fixed investment of {$fixed}.");
            }
            return $fixed;
        }

        // Ranged.
        $min = $this->toFloat($asset->min_amount);
        $max = $this->toFloat($asset->max_amount);
        if ($min === null || $max === null) {
            throw new InvalidArgumentException('A ranged asset must define min_amount and max_amount.');
        }
        if ($min > $max) {
            throw new InvalidArgumentException('min_amount cannot be greater than max_amount.');
        }
        if ($amount === null) {
            throw new InvalidArgumentException('An investment amount is required for this asset.');
        }
        if ($amount < $min || $amount > $max) {
            throw new InvalidArgumentException(
                "Investment amount {$amount} is outside the allowed range {$min}–{$max}."
            );
        }

        return (float) $amount;
    }

    private function resolveReturn(Asset $asset, float $principal): float
    {
        if ($this->normalizeReturnType($asset) === Asset::RETURN_PERCENTAGE) {
            $pct = $this->toFloat($asset->return_percentage);
            if ($pct === null) {
                throw new InvalidArgumentException('A percentage-return asset must define return_percentage.');
            }
            return $principal * ($pct / 100);
        }

        $fixedReturn = $this->toFloat($asset->fixed_return);
        if ($fixedReturn === null) {
            throw new InvalidArgumentException('A fixed-return asset must define fixed_return.');
        }
        return $fixedReturn;
    }

    private function normalizeAmountType(Asset $asset): string
    {
        $type = strtolower((string) $asset->amount_type);
        if (!in_array($type, [Asset::AMOUNT_FIXED, Asset::AMOUNT_RANGED], true)) {
            throw new InvalidArgumentException("Unknown amount_type: '{$asset->amount_type}'.");
        }
        return $type;
    }

    private function normalizeReturnType(Asset $asset): string
    {
        $type = strtolower((string) $asset->return_type);
        if (in_array($type, ['fixed', 'fixed_amount'], true)) {
            return Asset::RETURN_FIXED;
        }
        if ($type === Asset::RETURN_PERCENTAGE) {
            return Asset::RETURN_PERCENTAGE;
        }
        throw new InvalidArgumentException("Unknown return_type: '{$asset->return_type}'.");
    }

    private function toFloat($value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }
        return (float) $value;
    }
}

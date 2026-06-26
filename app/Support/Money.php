<?php

namespace App\Support;

use App\Models\Settings;
use Illuminate\Support\Facades\Auth;

/**
 * Currency formatting. The platform is open to investors worldwide, each of
 * whom chooses their display currency at sign-up. Amounts are shown using the
 * viewer's currency (logged-in user), falling back to the platform default
 * currency (AED) for guests.
 */
class Money
{
    public const DEFAULT_SYMBOL = 'AED';

    private static ?string $default = null;

    /**
     * Resolve the currency symbol/code for the current viewer.
     */
    public static function symbol(): string
    {
        $user = Auth::user();
        if ($user && !empty($user->currency)) {
            return $user->currency;
        }
        return self::defaultSymbol();
    }

    public static function defaultSymbol(): string
    {
        if (self::$default === null) {
            try {
                $settings = Settings::find(1);
                self::$default = ($settings && !empty($settings->getRawOriginal('currency')))
                    ? $settings->getRawOriginal('currency')
                    : self::DEFAULT_SYMBOL;
            } catch (\Throwable $e) {
                self::$default = self::DEFAULT_SYMBOL;
            }
        }
        return self::$default;
    }

    /**
     * Format an amount with the viewer's currency, e.g. "AED 1,500.00" or
     * "$1,500.00".
     */
    public static function display($amount): string
    {
        $symbol = self::symbol();
        $separator = strlen($symbol) > 1 ? ' ' : '';

        return $symbol . $separator . number_format((float) $amount, 2);
    }

    /**
     * Backwards-compatible alias used by the @pula Blade directive and any
     * legacy callers.
     */
    public static function pula($amount): string
    {
        return self::display($amount);
    }
}

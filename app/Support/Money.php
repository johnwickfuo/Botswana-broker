<?php

namespace App\Support;

/**
 * Currency formatting for the Republic of Botswana investment platform.
 * All investor-facing amounts are shown in Pula using the "P1,500.00" format.
 */
class Money
{
    public const SYMBOL = 'P';

    public static function pula($amount): string
    {
        return self::SYMBOL . number_format((float) $amount, 2);
    }
}

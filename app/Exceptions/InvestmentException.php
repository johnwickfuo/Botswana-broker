<?php

namespace App\Exceptions;

use RuntimeException;

/**
 * Thrown when an invest/redeem action violates a business rule (closed offer
 * window, capacity reached, insufficient balance, early redemption, …). The
 * message is safe to surface to the citizen.
 */
class InvestmentException extends RuntimeException
{
}

<?php

namespace App\Console\Commands;

use App\Services\PayoutService;
use Illuminate\Console\Command;

/**
 * Phase 6 — credits due return payouts and handles maturity (principal
 * repayment + marking investments matured) for the Republic of Botswana
 * investment platform. Scheduled daily.
 */
class ProcessInvestmentPayouts extends Command
{
    protected $signature = 'investments:process-payouts';

    protected $description = 'Credit due investment return payouts and process matured investments';

    public function handle(PayoutService $payouts): int
    {
        $this->info('Processing due investment payouts...');

        $summary = $payouts->processDue();

        $this->info(sprintf(
            'Done: %d payout(s) credited (%.2f total), %d investment(s) matured.',
            $summary['payouts'],
            $summary['credited'],
            $summary['matured']
        ));

        return self::SUCCESS;
    }
}

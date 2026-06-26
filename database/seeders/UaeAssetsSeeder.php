<?php

namespace Database\Seeders;

use App\Models\Asset;
use Illuminate\Database\Seeder;

/**
 * Seeds 10 real United Arab Emirates national assets/enterprises investors can
 * invest in, each with default investment terms (admin can adjust per asset).
 * Amounts are currency-neutral numbers — each investor sees them in their own
 * chosen currency.
 */
class UaeAssetsSeeder extends Seeder
{
    public function run(): void
    {
        $assets = [
            ['ADNOC (Abu Dhabi National Oil Company)', 'Energy & Oil', 'The Abu Dhabi national energy group, one of the world\'s largest oil and gas producers.', 1000, 500000, 12, 365],
            ['Emaar Properties', 'Real Estate', 'The leading UAE developer behind Burj Khalifa, Dubai Mall and major communities.', 1000, 300000, 11, 180],
            ['DP World', 'Ports & Logistics', 'A global ports, logistics and trade-enablement group headquartered in Dubai.', 1000, 400000, 10, 365],
            ['e& (Etisalat Group)', 'Telecommunications', 'The UAE\'s flagship telecommunications and technology group.', 500, 150000, 10, 90],
            ['Emirates NBD', 'Banking & Finance', 'One of the largest banking groups in the Middle East, based in Dubai.', 1000, 250000, 9, 365],
            ['Emirates Airline', 'Aviation', 'The Dubai-based international airline and a global aviation leader.', 1000, 350000, 11, 270],
            ['Aldar Properties', 'Real Estate', 'Abu Dhabi\'s leading real-estate developer and investment manager.', 500, 200000, 10.5, 180],
            ['DEWA (Dubai Electricity & Water Authority)', 'Utilities', 'Dubai\'s electricity and water utility, driving clean-energy projects.', 1000, 200000, 8.5, 365],
            ['Dubai Islamic Bank', 'Islamic Finance', 'The world\'s first full-service Islamic bank, headquartered in Dubai.', 500, 150000, 9.5, 180],
            ['Mubadala Investment Company', 'Sovereign Investment', 'Abu Dhabi\'s sovereign investor managing a global, diversified portfolio.', 2000, 1000000, 13, 365],
        ];

        foreach ($assets as [$name, $category, $description, $min, $max, $pct, $days]) {
            Asset::firstOrCreate(
                ['name' => $name],
                [
                    'category'          => $category,
                    'description'       => $description,
                    'status'            => 'active',
                    'amount_type'       => 'ranged',
                    'min_amount'        => $min,
                    'max_amount'        => $max,
                    'return_type'       => 'percentage',
                    'return_percentage' => $pct,
                    'duration'          => $days,
                    'duration_type'     => 'days',
                    'payout_interval'   => 'monthly',
                ]
            );
        }
    }
}

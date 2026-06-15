<?php

namespace Database\Seeders;

use App\Models\Asset;
use Illuminate\Database\Seeder;

/**
 * Seeds 10 real Botswana national assets/enterprises citizens can invest in,
 * each with default investment terms (admin can adjust these per asset).
 */
class BotswanaAssetsSeeder extends Seeder
{
    public function run(): void
    {
        $assets = [
            ['Debswana Diamond Company', 'Mining', 'The world-leading diamond producer, a partnership between the Government of Botswana and De Beers.', 1000, 500000, 12, 365],
            ['Morupule Coal Mine', 'Energy & Mining', 'Botswana\'s principal coal mine, supplying the nation\'s power stations.', 500, 200000, 9, 180],
            ['Botswana Power Corporation', 'Energy', 'The national electricity utility generating and distributing power across Botswana.', 1000, 300000, 8, 365],
            ['Water Utilities Corporation', 'Water & Sanitation', 'The national water utility responsible for water supply and sanitation.', 500, 150000, 7.5, 180],
            ['Botswana Telecommunications (BTCL)', 'Telecommunications', 'The national telecommunications operator, listed on the Botswana Stock Exchange.', 500, 100000, 10, 90],
            ['First National Bank Botswana', 'Finance & Banking', 'One of the largest banks in Botswana, listed on the Botswana Stock Exchange.', 1000, 250000, 11, 365],
            ['Letshego Holdings', 'Finance', 'A pan-African inclusive-finance group headquartered in Gaborone.', 500, 100000, 13, 180],
            ['Sefalana Holdings', 'Retail & FMCG', 'A leading wholesale, retail and consumer-goods group.', 500, 80000, 10.5, 180],
            ['Choppies Enterprises', 'Retail', 'A major retail supermarket chain operating across Botswana and the region.', 500, 60000, 9.5, 90],
            ['Botswana Meat Commission', 'Agriculture & Livestock', 'The national beef producer and exporter supporting the livestock sector.', 500, 120000, 8.5, 270],
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

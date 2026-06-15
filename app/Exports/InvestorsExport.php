<?php

namespace App\Exports;

use App\Models\User;
use App\Models\UserPlan;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

/**
 * Phase 7 — investors report (CSV/Excel): everyone who has invested, with
 * their aggregate position.
 */
class InvestorsExport implements FromCollection, WithHeadings, WithMapping
{
    private Collection $aggregates;

    public function collection()
    {
        $this->aggregates = UserPlan::selectRaw(
            'user_id, COUNT(*) as investments, SUM(invested_amount) as invested, SUM(total_profit) as earned'
        )->groupBy('user_id')->get()->keyBy('user_id');

        return User::whereIn('id', $this->aggregates->keys())->orderBy('id')->get();
    }

    public function headings(): array
    {
        return ['ID', 'Name', 'Email', 'Phone', 'KYC Status', 'Wallet Balance', 'Investments', 'Total Invested', 'Total Earned'];
    }

    public function map($user): array
    {
        $agg = $this->aggregates->get($user->id);

        return [
            $user->id,
            trim(($user->name ?? '') . ' ' . ($user->l_name ?? '')),
            $user->email,
            $user->phone,
            $user->account_verify,
            number_format((float) $user->account_bal, 2, '.', ''),
            (int) optional($agg)->investments,
            number_format((float) optional($agg)->invested, 2, '.', ''),
            number_format((float) optional($agg)->earned, 2, '.', ''),
        ];
    }
}

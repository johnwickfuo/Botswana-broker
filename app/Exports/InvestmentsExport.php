<?php

namespace App\Exports;

use App\Models\UserPlan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

/**
 * Phase 7 — investments report (CSV/Excel).
 */
class InvestmentsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return UserPlan::with(['investmentPlan.asset', 'investor'])->orderByDesc('id')->get();
    }

    public function headings(): array
    {
        return ['ID', 'Investor', 'Email', 'Plan', 'Asset', 'Principal', 'Expected Return', 'Earned', 'Status', 'Start', 'Maturity'];
    }

    public function map($investment): array
    {
        return [
            $investment->id,
            optional($investment->investor)->name,
            optional($investment->investor)->email,
            optional($investment->investmentPlan)->name,
            optional(optional($investment->investmentPlan)->asset)->name,
            number_format((float) $investment->invested_amount, 2, '.', ''),
            number_format((float) $investment->expected_return, 2, '.', ''),
            number_format((float) $investment->total_profit, 2, '.', ''),
            $investment->status,
            optional($investment->start_date)->format('Y-m-d'),
            optional($investment->maturity_date)->format('Y-m-d'),
        ];
    }
}

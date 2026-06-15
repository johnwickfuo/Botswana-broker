<?php

namespace App\Exports;

use App\Models\PlanPayout;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

/**
 * Phase 7 — payouts report (CSV/Excel).
 */
class PayoutsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return PlanPayout::with(['userPlan.investmentPlan', 'user'])->orderByDesc('id')->get();
    }

    public function headings(): array
    {
        return ['ID', 'Investor', 'Plan', 'Type', 'Amount', 'Status', 'Due Date', 'Processed At'];
    }

    public function map($payout): array
    {
        return [
            $payout->id,
            optional($payout->user)->name,
            optional(optional($payout->userPlan)->investmentPlan)->name,
            $payout->type,
            number_format((float) $payout->amount, 2, '.', ''),
            $payout->status,
            optional($payout->due_date)->format('Y-m-d'),
            optional($payout->processed_at)->format('Y-m-d H:i'),
        ];
    }
}

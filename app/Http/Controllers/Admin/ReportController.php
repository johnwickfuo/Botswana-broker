<?php

namespace App\Http\Controllers\Admin;

use App\Exports\InvestmentsExport;
use App\Exports\InvestorsExport;
use App\Exports\PayoutsExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Facades\Excel as ExcelFacade;

/**
 * Phase 7 — reports & exports (CSV/Excel) for investments, payouts and
 * investors.
 */
class ReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.index')->with('title','Reports');
    }

    public function investments()
    {
        return ExcelFacade::download(new InvestmentsExport, 'investments_' . date('Y-m-d') . '.csv', Excel::CSV);
    }

    public function payouts()
    {
        return ExcelFacade::download(new PayoutsExport, 'payouts_' . date('Y-m-d') . '.csv', Excel::CSV);
    }

    public function investors()
    {
        return ExcelFacade::download(new InvestorsExport, 'investors_' . date('Y-m-d') . '.csv', Excel::CSV);
    }
}

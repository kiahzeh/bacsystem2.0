<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\PurchaseRequestsMonthlyExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function generateMonthlyReport()
    {
        // For now, just redirect to dashboard or show a simple view
        return redirect()->route('dashboard')->with('info', 'Monthly report feature coming soon!');
    }

    public function exportMonthlyPurchaseRequests()
    {
        $date = Carbon::now();
        $filename = 'monthly_purchase_requests_' . $date->format('Y_m') . '.xlsx';
        return Excel::download(new PurchaseRequestsMonthlyExport, $filename);
    }
} 
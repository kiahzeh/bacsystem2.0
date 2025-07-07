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

    public function exportMonthlyPurchaseRequests()
    {
        $date = Carbon::now();
        $filename = 'monthly_purchase_requests_' . $date->format('Y_m') . '.xlsx';
        return Excel::download(new PurchaseRequestsMonthlyExport, $filename);
    }
} 
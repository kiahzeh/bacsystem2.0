<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseRequest;
use App\Models\Consolidate;  // Assuming you have this model for CPR
use Carbon\Carbon;
use App\Models\Document;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $search = $request->input('search');
        
        // Define all possible statuses
        $allStatuses = [
            'ATP',
            'Procurement',
            'Posting in PhilGEPS',
            'Pre-Bid',
            'Bid Opening',
            'Bid Evaluation',
            'Post Qualification',
            'Confirmation on Approval',
            'Issuance of Notice of Award',
            'Purchase Order',
            'Contract',
            'Notice to Proceed',
            'Posting of Award in PhilGEPS',
            'Forward Purchase or Supply'
        ];

        // Define valid statuses (same as all statuses for now, but can be customized)
        $validStatuses = $allStatuses;
        
        // Define enabled steps for the timeline
        $enabledSteps = [
            'ATP' => 'Approval to Purchase',
            'Procurement' => 'Procurement Planning',
            'Posting in PhilGEPS' => 'PhilGEPS Posting',
            'Pre-Bid' => 'Pre-Bid Conference',
            'Bid Opening' => 'Bid Opening',
            'Bid Evaluation' => 'Bid Evaluation',
            'Post Qualification' => 'Post Qualification',
            'Confirmation on Approval' => 'Approval Confirmation',
            'Issuance of Notice of Award' => 'Notice of Award',
            'Purchase Order' => 'Purchase Order',
            'Contract' => 'Contract Signing',
            'Notice to Proceed' => 'Notice to Proceed',
            'Posting of Award in PhilGEPS' => 'Award Posting',
            'Forward Purchase or Supply' => 'Purchase/Supply'
        ];
        
        // Always define these variables for the view
        $alternativeBids = PurchaseRequest::where('type', 'alternative')
            ->when($search, fn($query) => $query->where('pr_number', 'like', "%$search%"))
            ->get();

        $competitiveBids = PurchaseRequest::where('type', 'competitive')
            ->when($search, fn($query) => $query->where('pr_number', 'like', "%$search%"))
            ->get();

        $consolidatedPRs = Consolidate::with('purchaseRequests')->get();
        
        // Get purchase requests for the user
        $purchaseRequests = PurchaseRequest::where('user_id', $user->id)
            ->orWhere('department_id', $user->department_id)
            ->latest()
            ->take(5)
            ->get();

        // Get recent documents
        $recentDocuments = Document::whereHas('purchaseRequest', function($query) use ($user) {
            $query->where('user_id', $user->id)
                ->orWhere('department_id', $user->department_id);
        })->latest()->take(5)->get();

        // Get recent audit logs
        $recentAuditLogs = AuditLog::where('user_id', $user->id)
            ->orWhereHas('model', function($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhere('department_id', $user->department_id);
            })->latest()->take(10)->get();

        // Get statistics
        $stats = [
            'total_requests' => PurchaseRequest::where('user_id', $user->id)->count(),
            'pending_requests' => PurchaseRequest::where('user_id', $user->id)
                ->whereNotIn('status', ['completed', 'cancelled'])->count(),
            'total_documents' => Document::whereHas('purchaseRequest', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })->count(),
            'recent_activities' => $recentAuditLogs->count()
        ];

        // Get department statistics if user is department head
        $departmentStats = null;
        if (property_exists($user, 'is_department_head') && $user->is_department_head) {
            $departmentStats = [
                'total_requests' => PurchaseRequest::where('department_id', $user->department_id)->count(),
                'pending_requests' => PurchaseRequest::where('department_id', $user->department_id)
                    ->whereNotIn('status', ['completed', 'cancelled'])->count(),
                'total_documents' => Document::whereHas('purchaseRequest', function($query) use ($user) {
                    $query->where('department_id', $user->department_id);
                })->count()
            ];
        }

        $totalPRs = PurchaseRequest::count();
        $alternativeCount = PurchaseRequest::where('type', 'alternative')->count();
        $competitiveCount = PurchaseRequest::where('type', 'competitive')->count();
        $consolidatedCount = Consolidate::count();

        return view('dashboard', compact(
            'alternativeBids',
            'competitiveBids',
            'consolidatedPRs',
            'search',
            'purchaseRequests',
            'recentDocuments',
            'recentAuditLogs',
            'stats',
            'departmentStats',
            'enabledSteps',
            'allStatuses',
            'validStatuses',
            'totalPRs',
            'alternativeCount',
            'competitiveCount',
            'consolidatedCount'
        ));
    }

    public function store(Request $request)
    {
        // Get selected PR numbers
        $prNumbers = $request->input('pr_numbers');  // Array of selected PR IDs

        if (empty($prNumbers)) {
            return back()->withErrors(['error' => 'Please select at least one PR number to consolidate.']);
        }

        // Create a new CPR (Consolidated Purchase Request)
        $consolidate = new Consolidate();
        $consolidate->cpr_number = 'CPR-' . strtoupper(uniqid()); // Generate a unique CPR number (e.g., CPR-ABC123)
        $consolidate->created_by = auth()->user()->id;  // Assume you're storing the creator's user ID
        $consolidate->save();

        // Store the selected PR numbers in a relationship or JSON
        foreach ($prNumbers as $prId) {
            $pr = PurchaseRequest::find($prId);
            if ($pr) {
                $consolidate->purchaseRequests()->attach($pr); // Link PR to CPR (using a pivot table)
            }
        }

        // Optionally, you can store PR numbers in the CPR directly (as JSON or in a pivot table)
        return redirect()->route('dashboard')->with('success', 'CPR created successfully.');
    }

    public function showConsolidated()
    {
        // Fetch all consolidated PRs along with their related Purchase Requests (PRs)
        $consolidatedPRs = Consolidate::with('purchaseRequests')->get();
    
        // Pass the data to the view
        return view('consolidation.index', compact('consolidatedPRs'));
    }
    

}

class ReportController extends Controller
{
    public function generateMonthlyReport(Request $request)
    {
        // Get the current date
        $currentDate = Carbon::now();

        // Get the start and end date for the current month
        $startOfMonth = $currentDate->startOfMonth();
        $endOfMonth = $currentDate->endOfMonth();

        // Fetch purchase requests for the current month
        $purchaseRequests = PurchaseRequest::whereBetween('created_at', [$startOfMonth, $endOfMonth])
                                           ->get();

        // Calculate total funding (optional)
        $totalFunding = $purchaseRequests->sum('funding'); // assuming 'funding' is a field in the 'purchase_requests' table

        // Calculate other necessary statistics (example: total number of PRs)
        $totalPRs = $purchaseRequests->count();

        // Pass the data to the view
        return view('reports.monthly', compact('purchaseRequests', 'totalFunding', 'totalPRs', 'startOfMonth', 'endOfMonth'));
    }
}
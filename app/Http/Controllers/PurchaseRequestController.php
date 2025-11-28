<?php

namespace App\Http\Controllers;

use App\Models\PurchaseRequest;
use App\Models\Department;
use App\Models\Status;
use App\Models\User;
use App\Notifications\PurchaseRequestUpdated;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Consolidate;
use App\Notifications\DocumentUploaded;
use App\Models\Process;
use App\Models\Document;
use App\Models\AuditLog;
use App\Notifications\NewPurchaseRequestCreated;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PurchaseRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = PurchaseRequest::with(['user', 'department']);

        // If user is not an admin, only show PRs from their department
        if (!(auth()->user() && auth()->user()->role === 'admin')) {
            $query->where('department_id', auth()->user()->department_id);
        }

        $purchaseRequests = $query->latest()->paginate(10);
        return view('purchase-requests.index', compact('purchaseRequests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = Department::all();
        $statuses = Process::orderBy('order')->pluck('name')->toArray();
        return view('purchase-requests.create', compact('departments', 'statuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', PurchaseRequest::class);
    
        // If user is not an admin, force their department_id
        if (!(auth()->user() && auth()->user()->role === 'admin')) {
            $request->merge([
                'department_id' => auth()->user()->department_id,
            ]);
        }
    
        // Always attach the current user ID
        $request->merge([
            'user_id' => auth()->id(),
        ]);
    
        $validated = $request->validate([
            'pr_number' => 'required|string|max:255|unique:purchase_requests,pr_number',
            'name' => 'required|string|max:255',
            'project_title' => 'required|string|max:255',
            'order_date' => 'required|date',
            'department_id' => 'required|exists:departments,id',
            'status' => ['required', Rule::exists('processes', 'name')],
            'mode_of_procurement' => 'required|string|max:255',
            'abc_approved_budget' => 'required|numeric|min:0',
            'category' => 'required|string|max:255',
            'purpose_description' => 'required|string',
            'remarks' => 'nullable|string',
            'funding' => 'required|string',
            'user_id' => 'required|exists:users,id',
        ]);

        // Normalize funding
        $funding = $request->input('funding') === 'Others'
            ? $request->input('custom_funding')
            : $request->input('funding');
        $validated['funding'] = $funding;

        // Normalize mode of procurement
        $modeOfProcurement = $request->input('mode_of_procurement') === 'Others'
            ? $request->input('custom_mode_of_procurement')
            : $request->input('mode_of_procurement');
        $validated['mode_of_procurement'] = $modeOfProcurement;

        // Normalize category
        $category = $request->input('category') === 'Others'
            ? $request->input('custom_category')
            : $request->input('category');
        $validated['category'] = $category;
    
        // Create PR with normalized values
        $purchaseRequest = PurchaseRequest::create($validated);
        
        // Initialize workflow steps for new purchase request
        $defaultSteps = $purchaseRequest->getDefaultWorkflowSteps();
        $purchaseRequest->workflow_steps = $defaultSteps;
        $purchaseRequest->save();
        
        // Mark initial status as started with timestamp
        $purchaseRequest->statusHistory()->create([
            'status' => $purchaseRequest->status,
            'user_id' => auth()->id(),
            'started_at' => now(),
        ]);
    
        // Notify ALL users (except the creator) when a new PR is created
        $allUsers = User::where('id', '!=', auth()->id())->get();
        foreach ($allUsers as $user) {
            $user->notify(new NewPurchaseRequestCreated($purchaseRequest));
        }

        return redirect()->route('purchase-requests.index')->with('success', 'Purchase request created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(PurchaseRequest $purchaseRequest)
    {
        $this->authorize('view', $purchaseRequest);
        return view('purchase-requests.show', compact('purchaseRequest'));
    }

    /**
     * Show the completion form for a purchase request.
     */
    public function showCompleteForm(PurchaseRequest $purchaseRequest)
    {
        $this->authorize('update', $purchaseRequest);
        // Only admins see and use the completion form in the UI; policy guards update.
        return view('purchase-requests.complete', compact('purchaseRequest'));
    }

    /**
     * Store completion details and mark the PR as completed.
     */
    public function complete(Request $request, PurchaseRequest $purchaseRequest)
    {
        $this->authorize('update', $purchaseRequest);

        $validated = $request->validate([
            'completion_date' => 'required|date',
            'final_amount' => 'required|numeric|min:0',
            'awarded_vendor' => 'required|string|max:255',
            'contract_number' => 'nullable|string|max:255',
            'completion_notes' => 'nullable|string',
        ]);

        $oldStatus = $purchaseRequest->status;

        // Apply completion fields
        $purchaseRequest->completion_date = $validated['completion_date'];
        $purchaseRequest->final_amount = $validated['final_amount'];
        $purchaseRequest->awarded_vendor = $validated['awarded_vendor'];
        $purchaseRequest->contract_number = $validated['contract_number'] ?? null;
        $purchaseRequest->completion_notes = $validated['completion_notes'] ?? null;

        // Mark status as Completed and update metadata
        $purchaseRequest->status = 'Completed';
        $purchaseRequest->last_modified_by = auth()->id();
        $purchaseRequest->last_modified_at = now();
        $purchaseRequest->save();

        // Update status history timestamps
        if ($oldStatus && $oldStatus !== 'Completed') {
            $purchaseRequest->statusHistory()->updateOrCreate(
                ['status' => $oldStatus],
                [
                    'user_id' => auth()->id(),
                    'completed_at' => now(),
                ]
            );
        }

        // Ensure Completed status is tracked as started and completed
        $purchaseRequest->statusHistory()->updateOrCreate(
            ['status' => 'Completed'],
            [
                'user_id' => auth()->id(),
                'started_at' => now(),
                'completed_at' => now(),
            ]
        );

        // Notify all other users
        $allUsers = \App\Models\User::where('id', '!=', auth()->id())->get();
        foreach ($allUsers as $user) {
            $user->notify(new \App\Notifications\PurchaseRequestUpdated($purchaseRequest, $oldStatus, 'completed'));
        }

        return redirect()->route('purchase-requests.show', $purchaseRequest)
            ->with('success', 'Purchase request marked as completed.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PurchaseRequest $purchaseRequest)
    {
        $this->authorize('update', $purchaseRequest);
        $departments = Department::all();
        $statuses = Process::orderBy('order')->pluck('name')->toArray();
        return view('purchase-requests.edit', compact('purchaseRequest', 'departments', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PurchaseRequest $purchaseRequest)
    {
        $this->authorize('update', $purchaseRequest);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'order_date' => 'required|date',
            'department_id' => 'nullable|exists:departments,id',
            'status' => ['nullable', Rule::exists('processes', 'name')],
            'mode_of_procurement' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'remarks' => 'nullable|string',
        ]);

        // Normalize mode of procurement
        $modeOfProcurement = $request->input('mode_of_procurement') === 'Others'
            ? $request->input('custom_mode_of_procurement')
            : $request->input('mode_of_procurement');
        $validated['mode_of_procurement'] = $modeOfProcurement;

        // Normalize category
        $category = $request->input('category') === 'Others'
            ? $request->input('custom_category')
            : $request->input('category');
        $validated['category'] = $category;

        // If user is not an admin, force their department_id
        if (!(auth()->user() && auth()->user()->role === 'admin')) {
            $validated['department_id'] = auth()->user()->department_id;
        }

        // If status is not provided, keep the current status
        if (empty($validated['status'])) {
            $validated['status'] = $purchaseRequest->status;
        }

        // If department_id is not provided, keep the current department_id
        if (empty($validated['department_id'])) {
            $validated['department_id'] = $purchaseRequest->department_id;
        }

        $oldStatus = $purchaseRequest->status;
        $purchaseRequest->fill($validated);
        $purchaseRequest->last_modified_by = auth()->id();
        $purchaseRequest->last_modified_at = now();
        $purchaseRequest->save();

        // Handle status change timestamps
        if ($oldStatus !== $purchaseRequest->status) {
            // Mark old status as completed
            if ($oldStatus) {
                $purchaseRequest->statusHistory()->updateOrCreate(
                    ['status' => $oldStatus],
                    [
                        'user_id' => auth()->id(),
                        'completed_at' => now(),
                    ]
                );
            }
            
            // Mark new status as started
            $purchaseRequest->statusHistory()->updateOrCreate(
                ['status' => $purchaseRequest->status],
                [
                    'user_id' => auth()->id(),
                    'started_at' => now(),
                ]
            );
        }

        // Notify ALL users (except the creator) when PR is updated
        $allUsers = User::where('id', '!=', auth()->id())->get();
        foreach ($allUsers as $user) {
            $user->notify(new PurchaseRequestUpdated($purchaseRequest, $oldStatus, 'updated'));
        }

        return redirect()->route('purchase-requests.index')
            ->with('success', 'Purchase request updated successfully.');
    }

    public function timeline(PurchaseRequest $purchaseRequest)
    {
        $this->authorize('view', $purchaseRequest);
        
        // Get the workflow steps for this PR
        $allStatuses = $purchaseRequest->workflow_steps ?? [];
        
        // If no custom workflow steps exist, use the default workflow steps
        if (empty($allStatuses)) {
            $allStatuses = $purchaseRequest->getDefaultWorkflowSteps();
            
            // Initialize the workflow steps for this PR
            $purchaseRequest->workflow_steps = $allStatuses;
            $purchaseRequest->save();
        }
        
        // Ensure the current status is in the workflow steps
        if (!in_array($purchaseRequest->status, $allStatuses)) {
            // If the current status is not in the workflow, add it at the beginning
            array_unshift($allStatuses, $purchaseRequest->status);
            $purchaseRequest->workflow_steps = $allStatuses;
            $purchaseRequest->save();
        }
        
        return view('purchase-requests.timeline', compact('purchaseRequest', 'allStatuses'));
    }

    /**
     * Upload a document for the given purchase request from the timeline.
     */
    public function uploadDocument(Request $request, PurchaseRequest $purchaseRequest)
    {
        // Allow users who can view the PR (department members and admins) to upload
        $this->authorize('view', $purchaseRequest);

        $validated = $request->validate([
            'document' => 'required|file|max:10240',
            'status' => 'required|string',
        ]);

        $file = $request->file('document');
        $originalFilename = $file->getClientOriginalName();
        $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();

        // Store the file in the public disk under documents/{PR_ID}
        $path = $file->storeAs('documents/' . $purchaseRequest->id, $filename, 'public');

        // Create document record
        $document = Document::create([
            'purchase_request_id' => $purchaseRequest->id,
            'filename' => $filename,
            'original_filename' => $originalFilename,
            'path' => $path,
            'status' => $validated['status'],
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'uploaded_by' => auth()->id(),
        ]);

        // Fire notifications (department head, procurement, admins)
        try {
            $this->notifyDocumentUpload($purchaseRequest->loadMissing('department'), $document);
        } catch (\Throwable $e) {
            Log::warning('Failed to send document upload notifications', [
                'error' => $e->getMessage(),
                'purchase_request_id' => $purchaseRequest->id,
                'document_id' => $document->id ?? null,
            ]);
        }

        return redirect()->back()->with('success', 'Document uploaded successfully.');
    }

    // Export a single purchase request as a downloadable PDF
    public function export(PurchaseRequest $purchaseRequest)
    {
        $this->authorize('view', $purchaseRequest);

        // Ensure related data needed by the export template is available
        $purchaseRequest->loadMissing('department');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('purchase-requests.export', compact('purchaseRequest'))
            ->setPaper('a4');

        $filename = 'PR-' . ($purchaseRequest->pr_number ?? $purchaseRequest->id) . '.pdf';
        return $pdf->download($filename);
    }

    public function updateSteps(Request $request, PurchaseRequest $purchaseRequest)
    {
        $request->validate([
            'enabled_steps' => 'required|array',
            'enabled_steps.*' => 'string'
        ]);

        $purchaseRequest->update([
            'enabled_steps' => $request->enabled_steps
        ]);

        return redirect()->back()->with('success', 'Process steps updated successfully.');
    }

    private function notifyDocumentUpload($purchaseRequest, $document)
    {
        // Notify department head
        if ($purchaseRequest->department->head) {
            $purchaseRequest->department->head->notify(new DocumentUploaded($purchaseRequest, $document));
        }

        // Notify procurement staff
        $procurementUsers = User::where('role', 'procurement')->get();

        foreach ($procurementUsers as $user) {
            $user->notify(new DocumentUploaded($purchaseRequest, $document));
        }

        // Notify admins
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new DocumentUploaded($purchaseRequest, $document));
        }
    }

    public function confirmSkipWorkflowStep(PurchaseRequest $purchaseRequest, $stepIndex)
    {
        $this->authorize('update', $purchaseRequest);
        
        $steps = $purchaseRequest->workflow_steps ?? [];
        if (!isset($steps[$stepIndex])) {
            return back()->with('error', 'Step not found.');
        }
        
        $stepName = $steps[$stepIndex];
        $nextStep = $steps[$stepIndex + 1] ?? (in_array('Completed', $steps) ? 'Completed' : $stepName);
        
        return view('purchase-requests.confirm-skip-step', compact('purchaseRequest', 'stepIndex', 'stepName', 'nextStep'));
    }

    public function skipWorkflowStep(Request $request, PurchaseRequest $purchaseRequest, $stepIndex)
    {
        $this->authorize('update', $purchaseRequest);

        // Ensure department relationship is available for recipient selection
        $purchaseRequest->loadMissing('department');

        $steps = $purchaseRequest->workflow_steps ?? [];
        if (!isset($steps[$stepIndex])) {
            return redirect()->route('purchase-requests.timeline', $purchaseRequest)
                ->with('error', 'Step not found.');
        }

        $stepName = $steps[$stepIndex];
        $oldStatus = $stepName;

        // Guard: if this step was already skipped, do not re-skip
        $existingStatusHistory = $purchaseRequest->statusHistory()->where('status', $stepName)->first();
        if ($existingStatusHistory && $existingStatusHistory->is_skipped) {
            return redirect()->route('purchase-requests.timeline', $purchaseRequest)
                ->with('error', "Step '{$stepName}' was already skipped.");
        }

        // Mark the selected step as skipped in status history
        $purchaseRequest->statusHistory()->updateOrCreate(
            ['status' => $stepName],
            [
                'user_id' => auth()->id(),
                'is_skipped' => true,
                'completed_at' => now(),
            ]
        );

        // If skipping the current step, advance to the next step
        if ($purchaseRequest->status === $stepName) {
            $nextIndex = $stepIndex + 1;
            if ($nextIndex < count($steps)) {
                $nextStep = $steps[$nextIndex];
            } else {
                // If there is no next step, keep current status or set to 'Completed' if present
                $nextStep = in_array('Completed', $steps) ? 'Completed' : $stepName;
            }

            // Update PR status and timestamps
            $purchaseRequest->status = $nextStep;
            $purchaseRequest->last_modified_by = auth()->id();
            $purchaseRequest->last_modified_at = now();
            $purchaseRequest->save();

            // Mark next step as started
            $purchaseRequest->statusHistory()->updateOrCreate(
                ['status' => $nextStep],
                [
                    'user_id' => auth()->id(),
                    'started_at' => now(),
                ]
            );
        } else {
            // If skipping a non-current step, still update last modified metadata
            $purchaseRequest->last_modified_by = auth()->id();
            $purchaseRequest->last_modified_at = now();
            $purchaseRequest->save();
        }

        // Notify relevant recipients (department head, procurement, admins)
        $recipients = $this->getWorkflowRecipients($purchaseRequest);
        foreach ($recipients as $user) {
            $user->notify(new PurchaseRequestUpdated($purchaseRequest, $oldStatus, 'step_skipped'));
        }

        return redirect()->route('purchase-requests.timeline', $purchaseRequest)
            ->with('success', "Step '{$stepName}' has been skipped.");
    }

    // New: confirm advancing to the next workflow step
    public function confirmNextWorkflowStep(PurchaseRequest $purchaseRequest, $stepIndex)
    {
        $this->authorize('update', $purchaseRequest);

        $steps = $purchaseRequest->workflow_steps ?? [];
        if (!isset($steps[$stepIndex])) {
            return back()->with('error', 'Step not found.');
        }

        $currentStep = $steps[$stepIndex];
        $nextStep = $steps[$stepIndex + 1] ?? (in_array('Completed', $steps) ? 'Completed' : $currentStep);

        return view('purchase-requests.confirm-next-step', compact('purchaseRequest', 'stepIndex', 'currentStep', 'nextStep'));
    }

    // New: advance to the next workflow step
    public function nextWorkflowStep(Request $request, PurchaseRequest $purchaseRequest, $stepIndex)
    {
        $this->authorize('update', $purchaseRequest);

        // Ensure department relationship is available for recipient selection
        $purchaseRequest->loadMissing('department');

        $steps = $purchaseRequest->workflow_steps ?? [];
        if (!isset($steps[$stepIndex])) {
            return redirect()->route('purchase-requests.timeline', $purchaseRequest)
                ->with('error', 'Step not found.');
        }

        $currentStep = $steps[$stepIndex];
        $oldStatus = $currentStep;
        $nextStep = $steps[$stepIndex + 1] ?? (in_array('Completed', $steps) ? 'Completed' : $currentStep);

        // Mark current step as completed
        $purchaseRequest->statusHistory()->updateOrCreate(
            ['status' => $currentStep],
            [
                'user_id' => auth()->id(),
                'completed_at' => now(),
            ]
        );

        // Advance status to next step
        $purchaseRequest->status = $nextStep;
        $purchaseRequest->last_modified_by = auth()->id();
        $purchaseRequest->last_modified_at = now();
        $purchaseRequest->save();

        // Mark next step as started
        $purchaseRequest->statusHistory()->updateOrCreate(
            ['status' => $nextStep],
            [
                'user_id' => auth()->id(),
                'started_at' => now(),
            ]
        );

        // Notify relevant recipients (department head, procurement, admins)
        $recipients = $this->getWorkflowRecipients($purchaseRequest);
        foreach ($recipients as $user) {
            $user->notify(new PurchaseRequestUpdated($purchaseRequest, $oldStatus, 'status_advanced'));
        }

        return redirect()->route('purchase-requests.timeline', $purchaseRequest)
            ->with('success', "Advanced from '{$currentStep}' to '{$nextStep}'.");
    }

    public function confirmRemoveWorkflowStep(PurchaseRequest $purchaseRequest, $stepIndex)
    {
        $this->authorize('update', $purchaseRequest);

        $steps = $purchaseRequest->workflow_steps ?? [];
        if (!isset($steps[$stepIndex])) {
            return back()->with('error', 'Step not found.');
        }

        $stepName = $steps[$stepIndex];

        return view('purchase-requests.confirm-remove-step', compact('purchaseRequest', 'stepIndex', 'stepName'));
    }

    public function removeWorkflowStep(Request $request, PurchaseRequest $purchaseRequest, $stepIndex)
    {
        $this->authorize('update', $purchaseRequest);

        // Ensure department relationship is available for recipient selection
        $purchaseRequest->loadMissing('department');

        $steps = $purchaseRequest->workflow_steps ?? [];
        if (!isset($steps[$stepIndex])) {
            return redirect()->route('purchase-requests.timeline', $purchaseRequest)
                ->with('error', 'Step not found.');
        }

        $stepName = $steps[$stepIndex];
        $oldStatus = $stepName;

        // Remove the step and reindex
        array_splice($steps, (int)$stepIndex, 1);
        $steps = array_values($steps);
        $purchaseRequest->workflow_steps = $steps;

        // Update metadata
        $purchaseRequest->last_modified_by = auth()->id();
        $purchaseRequest->last_modified_at = now();

        // If the removed step is the current status, move to a sensible next step
        if ($purchaseRequest->status === $stepName) {
            $nextStep = $steps[$stepIndex] ?? (in_array('Completed', $steps) ? 'Completed' : ($steps[$stepIndex - 1] ?? $purchaseRequest->status));
            $purchaseRequest->status = $nextStep;
            $purchaseRequest->save();

            if ($nextStep !== $oldStatus) {
                $purchaseRequest->statusHistory()->updateOrCreate(
                    ['status' => $nextStep],
                    [
                        'user_id' => auth()->id(),
                        'started_at' => now(),
                    ]
                );
            }
        } else {
            $purchaseRequest->save();
        }

        // Notify relevant recipients (department head, procurement, admins)
        $recipients = $this->getWorkflowRecipients($purchaseRequest);
        foreach ($recipients as $user) {
            $user->notify(new PurchaseRequestUpdated($purchaseRequest, $oldStatus, 'step_removed'));
        }

        return redirect()->route('purchase-requests.timeline', $purchaseRequest)
            ->with('success', "Step '{$stepName}' has been removed.");
    }

    /**
     * Select relevant recipients for workflow updates.
     * Includes department head, procurement users, and admins; excludes the actor.
     */
    private function getWorkflowRecipients(PurchaseRequest $purchaseRequest)
    {
        $actorId = auth()->id();
        $recipients = collect();

        // Department head
        if ($purchaseRequest->department && $purchaseRequest->department->head) {
            $recipients->push($purchaseRequest->department->head);
        }

        // Procurement users
        $recipients = $recipients->merge(User::where('role', 'procurement')->get());

        // Admins
        $recipients = $recipients->merge(User::where('role', 'admin')->get());

        // Unique by user id and exclude actor
        return $recipients->unique('id')->filter(function ($user) use ($actorId) {
            return $user->id !== $actorId;
        });
    }

    public function generateMonthlyReport(Request $request)
    {
        // Get the current date
        $currentDate = now();

        // Get the start and end date for the current month
        $startOfMonth = $currentDate->startOfMonth();
        $endOfMonth = $currentDate->endOfMonth();

        // Fetch purchase requests for the current month
        $purchaseRequests = PurchaseRequest::whereBetween('created_at', [$startOfMonth, $endOfMonth])
                                           ->get();

        // Calculate total funding (optional)
        $totalFunding = $purchaseRequests->sum('funding');

        // Calculate other necessary statistics (example: total number of PRs)
        $totalPRs = $purchaseRequests->count();

        // Pass the data to the view
        return view('reports.monthly', compact('purchaseRequests', 'totalFunding', 'totalPRs', 'startOfMonth', 'endOfMonth'));
    }

    public function ajaxSearch(Request $request)
    {
        $query = trim($request->get('q', ''));
        if ($query === '') {
            return response()->json([]);
        }

        $user = auth()->user();
        $baseQuery = PurchaseRequest::query();

        // Restrict non-admins to their department
        if (!($user && $user->role === 'admin')) {
            $baseQuery->where('department_id', $user->department_id);
        }

        $results = $baseQuery
            ->where(function($q) use ($query) {
                $q->where('pr_number', 'like', "%{$query}%")
                  ->orWhere('name', 'like', "%{$query}%")
                  ->orWhere('mode_of_procurement', 'like', "%{$query}%");
            })
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get(['id', 'pr_number', 'name', 'status', 'mode_of_procurement']);

        return response()->json($results);
    }
}

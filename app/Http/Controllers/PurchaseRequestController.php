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
        if (!auth()->user()->isAdmin()) {
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
        if (!auth()->user()->isAdmin()) {
            $request->merge([
                'department_id' => auth()->user()->department_id,
            ]);
        }
    
        // Always attach the current user ID
        $request->merge([
            'user_id' => auth()->id(),
        ]);
    
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'order_date' => 'required|date',
            'department_id' => 'required|exists:departments,id',
            'status' => ['required', Rule::exists('processes', 'name')],
            'type' => 'required|in:alternative,competitive', // ✅ Added type validation
            'remarks' => 'nullable|string',
            'funding' => 'required|string',
            'user_id' => 'required|exists:users,id',
        ]);

        $funding = $request->input('funding') === 'Others'
            ? $request->input('custom_funding')
            : $request->input('funding');

        $request->merge(['funding' => $funding]);
    
        $purchaseRequest = PurchaseRequest::create($validated);

        $lastPR = PurchaseRequest::latest('id')->first();
        $nextNumber = $lastPR ? $lastPR->id + 1 : 1;
        $prNumber = 'PR-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

        $purchaseRequest->update(['pr_number' => $prNumber]);
        
        // Initialize workflow steps for new purchase request
        $defaultSteps = $purchaseRequest->getDefaultWorkflowSteps();
        $purchaseRequest->workflow_steps = $defaultSteps;
        $purchaseRequest->save();
    
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
            'remarks' => 'nullable|string',
        ]);

        // If user is not an admin, force their department_id
        if (!auth()->user()->isAdmin()) {
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

        // Notify ALL users (except the creator) when PR is updated
        $allUsers = User::where('id', '!=', auth()->id())->get();
        foreach ($allUsers as $user) {
            $user->notify(new PurchaseRequestUpdated($purchaseRequest, $oldStatus, 'updated'));
        }

        return redirect()->route('purchase-requests.index')
            ->with('success', 'Purchase request updated successfully.');
    }

    /**
     * Show delete confirmation page.
     */
    public function deleteConfirm(PurchaseRequest $purchaseRequest)
    {
        $this->authorize('delete', $purchaseRequest);
        return view('purchase-requests.delete-confirm', compact('purchaseRequest'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PurchaseRequest $purchaseRequest)
    {
        $this->authorize('delete', $purchaseRequest);

        $purchaseRequest->delete();

        return redirect()->route('purchase-requests.index')
            ->with('success', 'Purchase request deleted successfully.');
    }

    /**
     * Display the timeline view for the purchase request.
     */
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

    public function storeConsolidation(Request $request)
    {
        $request->validate([
            'pr_ids' => 'required|array'
        ]);

        $cpr = Consolidate::create();
        $cpr->purchaseRequests()->attach($request->pr_ids);

        return redirect()->back()->with('success', 'CPR Created!');
    }

    public function competitiveList(Request $request)
    {
        $query = PurchaseRequest::with(['department'])
            ->where('type', 'competitive');

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('pr_number', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }

        // Apply status filter
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $purchaseRequests = $query->paginate(10);
        $statuses = Process::orderBy('order')->pluck('name')->toArray();

        return view('purchase-requests.competitive-list', compact('purchaseRequests', 'statuses'));
    }

    /**
     * Upload a document for a specific step in the timeline.
     */
    public function uploadDocument(Request $request, PurchaseRequest $purchaseRequest)
    {
        $request->validate([
            'document' => 'required|file|max:10240', // 10MB max
            'status' => 'required|string'
        ]);

        $file = $request->file('document');
        $originalFilename = $file->getClientOriginalName();
        $filename = time() . '_' . $originalFilename;
        
        // Store the file
        $path = $file->storeAs('documents', $filename, 'public');
        
        // Create document record
        $document = $purchaseRequest->documents()->create([
            'filename' => $filename,
            'original_filename' => $originalFilename,
            'path' => $path,
            'status' => $request->status,
            'uploaded_by' => auth()->id(),
            'mime_type' => $file->getClientMimeType(),
            'file_size' => $file->getSize(),
        ]);

        // Create notification for relevant users
        $this->notifyDocumentUpload($purchaseRequest, $document);

        return redirect()->back()->with('success', 'Document uploaded successfully.');
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

    public function ajaxSearch(Request $request)
    {
        $query = $request->input('q');
        $results = PurchaseRequest::where('pr_number', 'like', "%$query%")
            ->orWhere('name', 'like', "%$query%")
            ->limit(10)
            ->get(['id', 'pr_number', 'name', 'status']);
        return response()->json($results);
    }

    public function addWorkflowStep(Request $request, PurchaseRequest $purchaseRequest)
    {
        $this->authorize('update', $purchaseRequest);
        
        $request->validate([
            'step_name' => 'required|string|max:255',
        ]);
        
        $stepName = $request->step_name;
        $steps = $purchaseRequest->workflow_steps ?? [];
        $steps[] = $stepName;
        $purchaseRequest->workflow_steps = $steps;
        $purchaseRequest->save();
        
        // Notify ALL users (except the creator) when a step is added
        $allUsers = User::where('id', '!=', auth()->id())->get();
        foreach ($allUsers as $user) {
            $user->notify(new PurchaseRequestUpdated($purchaseRequest, $stepName, 'step_added'));
        }
        
        return back()->with('success', 'Step added successfully.');
    }

    public function getAvailableProcesses()
    {
        // Get all available process templates from the processes table
        return Process::orderBy('order')->pluck('name')->toArray();
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

    public function confirmSkipWorkflowStep(PurchaseRequest $purchaseRequest, $stepIndex)
    {
        $this->authorize('update', $purchaseRequest);
        
        $steps = $purchaseRequest->workflow_steps ?? [];
        if (!isset($steps[$stepIndex])) {
            return back()->with('error', 'Step not found.');
        }
        
        $stepName = $steps[$stepIndex];
        
        return view('purchase-requests.confirm-skip-step', compact('purchaseRequest', 'stepIndex', 'stepName'));
    }

    public function confirmNextWorkflowStep(PurchaseRequest $purchaseRequest, $stepIndex)
    {
        $this->authorize('update', $purchaseRequest);
        
        $steps = $purchaseRequest->workflow_steps ?? [];
        if (!isset($steps[$stepIndex]) || $stepIndex >= count($steps) - 1) {
            return back()->with('error', 'Cannot advance to next step.');
        }
        
        $currentStep = $steps[$stepIndex];
        $nextStep = $steps[$stepIndex + 1];
        
        return view('purchase-requests.confirm-next-step', compact('purchaseRequest', 'stepIndex', 'currentStep', 'nextStep'));
    }

    public function confirmResetWorkflowToDefault(PurchaseRequest $purchaseRequest)
    {
        $this->authorize('update', $purchaseRequest);
        
        $currentSteps = $purchaseRequest->workflow_steps ?? [];
        $defaultStepsData = $purchaseRequest->getDefaultWorkflowSteps();
        $defaultSteps = array_keys($defaultStepsData); // Extract just the step names
        
        return view('purchase-requests.confirm-reset-to-default', compact('purchaseRequest', 'currentSteps', 'defaultSteps'));
    }

    public function removeWorkflowStep(PurchaseRequest $purchaseRequest, $stepIndex)
    {
        $steps = $purchaseRequest->workflow_steps ?? [];
        if (isset($steps[$stepIndex])) {
            $stepName = $steps[$stepIndex];
            array_splice($steps, $stepIndex, 1);
            $purchaseRequest->workflow_steps = $steps;
            $purchaseRequest->save();
            
            // Notify ALL users (except the creator) when a step is removed
            $allUsers = User::where('id', '!=', auth()->id())->get();
            foreach ($allUsers as $user) {
                $user->notify(new PurchaseRequestUpdated($purchaseRequest, $stepName, 'step_removed'));
            }
            
            return back()->with('success', 'Step removed successfully.');
        }
        return back()->with('error', 'Step not found.');
    }

    public function skipWorkflowStep(PurchaseRequest $purchaseRequest, $stepIndex)
    {
        $steps = $purchaseRequest->workflow_steps ?? [];
        if (isset($steps[$stepIndex])) {
            $stepName = $steps[$stepIndex];
            
            // Mark as skipped in status history
            $history = $purchaseRequest->statusHistory()->where('status', $stepName)->first();
            if ($history) {
                $history->is_skipped = true;
                $history->save();
            } else {
                $purchaseRequest->statusHistory()->create([
                    'status' => $stepName,
                    'is_skipped' => true,
                    'user_id' => auth()->id(),
                ]);
            }
            
            // Notify ALL users (except the creator) when a step is skipped
            $allUsers = User::where('id', '!=', auth()->id())->get();
            foreach ($allUsers as $user) {
                $user->notify(new PurchaseRequestUpdated($purchaseRequest, $stepName, 'step_skipped'));
            }
            
            return back()->with('success', 'Step skipped successfully.');
        }
        return back()->with('error', 'Step not found.');
    }

    public function nextWorkflowStep(PurchaseRequest $purchaseRequest, $stepIndex)
    {
        $this->authorize('update', $purchaseRequest);
        
        $steps = $purchaseRequest->workflow_steps ?? [];
        if (!isset($steps[$stepIndex]) || $stepIndex >= count($steps) - 1) {
            return back()->with('error', 'Cannot advance to next step.');
        }
        
        $currentStep = $steps[$stepIndex];
        $nextStep = $steps[$stepIndex + 1];
        
        // Mark current step as completed
        $purchaseRequest->statusHistory()->updateOrCreate(
            ['status' => $currentStep],
            [
                'user_id' => auth()->id(),
                'is_skipped' => false,
            ]
        );
        
        // Update PR status to next step
        $purchaseRequest->status = $nextStep;
        $purchaseRequest->last_modified_by = auth()->id();
        $purchaseRequest->last_modified_at = now();
        $purchaseRequest->save();
        
        // Notify ALL users (except the creator) when workflow is advanced
        $allUsers = User::where('id', '!=', auth()->id())->get();
        foreach ($allUsers as $user) {
            $user->notify(new PurchaseRequestUpdated($purchaseRequest, $currentStep, 'status_advanced'));
        }
        
        return back()->with('success', "Workflow advanced from '{$currentStep}' to '{$nextStep}' successfully.");
    }

    public function resetWorkflowToDefault(PurchaseRequest $purchaseRequest)
    {
        $this->authorize('update', $purchaseRequest);
        
        // Reset to default processes from the processes table
        $defaultSteps = Process::orderBy('order')->pluck('name')->toArray();
        $purchaseRequest->workflow_steps = $defaultSteps;
        $purchaseRequest->save();
        
        // Notify ALL users (except the creator) when workflow is reset to default
        $allUsers = User::where('id', '!=', auth()->id())->get();
        foreach ($allUsers as $user) {
            $user->notify(new PurchaseRequestUpdated($purchaseRequest, 'default', 'workflow_reset'));
        }
        
        return back()->with('success', 'Workflow reset to default steps successfully.');
    }

    public function convertType(PurchaseRequest $purchaseRequest)
    {
        $oldType = $purchaseRequest->type;
        $newType = $oldType === 'alternative' ? 'competitive' : 'alternative';
        
        $purchaseRequest->type = $newType;
        $purchaseRequest->last_modified_by = auth()->id();
        $purchaseRequest->last_modified_at = now();
        $purchaseRequest->save();
        
        // Notify ALL users (except the creator) when type is changed
        $allUsers = User::where('id', '!=', auth()->id())->get();
        foreach ($allUsers as $user) {
            $user->notify(new PurchaseRequestUpdated($purchaseRequest, $oldType, 'type_changed'));
        }
        
        return redirect()->route('purchase-requests.edit', $purchaseRequest)
            ->with('success', "Purchase Request type successfully converted from {$oldType} to {$newType}.");
    }

}

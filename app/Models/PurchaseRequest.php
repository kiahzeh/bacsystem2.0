<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'order_date',
        'department_id',
        'status',
        'remarks',
        'type', 
        'user_id', 
        'funding',
        'workflow_steps',
        'current_step_index',
        'is_workflow_customized',
        'last_modified_by',
        'last_modified_at',
    ];

    protected $casts = [
        'order_date' => 'date',
        'enabled_steps' => 'array',
        'workflow_steps' => 'array',
        'is_workflow_customized' => 'boolean'
    ];

    /**
     * Get the user that created the purchase request.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the department that owns the purchase request.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the user that last updated the purchase request.
     */
    public function lastModifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'last_modified_by');
    }

    public function getRouteKeyName()
    {
        return 'id'; // Make sure Laravel binds by id
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($request) {
            $lastId = self::max('id');
            $request->pr_number = 'PR-' . str_pad($lastId + 1, 5, '0', STR_PAD_LEFT);
            
            // Initialize default workflow steps if not customized
            if (!$request->workflow_steps) {
                $request->workflow_steps = array_keys($request->getDefaultWorkflowSteps());
            }
        });
    }

    public function getDefaultWorkflowSteps()
    {
        return [
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
    }

    public function getCurrentStep()
    {
        if (!isset($this->workflow_steps[$this->current_step_index])) {
            return null;
        }
        return $this->workflow_steps[$this->current_step_index];
    }

    public function getNextStep()
    {
        if (!isset($this->workflow_steps[$this->current_step_index + 1])) {
            return null;
        }
        return $this->workflow_steps[$this->current_step_index + 1];
    }

    public function getPreviousStep()
    {
        if ($this->current_step_index <= 0) {
            return null;
        }
        return $this->workflow_steps[$this->current_step_index - 1];
    }

    public function getWorkflowProgress()
    {
        $totalSteps = count($this->workflow_steps);
        $completedSteps = $this->statusHistory()
            ->where('completed_at', '!=', null)
            ->orWhere('is_skipped', true)
            ->count();
        
        return ($completedSteps / $totalSteps) * 100;
    }

    public function customizeWorkflow(array $steps)
    {
        $this->workflow_steps = $steps;
        $this->is_workflow_customized = true;
        $this->current_step_index = 0;
        $this->save();
    }

    public function addWorkflowStep($step, $position = null)
    {
        $steps = $this->workflow_steps;
        if ($position === null) {
            $steps[] = $step;
        } else {
            array_splice($steps, $position, 0, [$step]);
        }
        $this->workflow_steps = $steps;
        $this->save();
    }

    public function removeWorkflowStep($position)
    {
        $steps = $this->workflow_steps;
        array_splice($steps, $position, 1);
        $this->workflow_steps = $steps;
        $this->save();
    }

    public function reorderWorkflowSteps(array $newOrder)
    {
        $this->workflow_steps = $newOrder;
        $this->save();
    }

    public function consolidatedRequests()
    {
        return $this->belongsToMany(Consolidate::class, 'consolidate_purchase_request', 'pr_id', 'consolidate_id');
    }

    public function consolidatedGroups()
    {
        return $this->belongsToMany(Consolidate::class, 'consolidate_request', 'pr_id', 'consolidate_id');
    }

    public function statusHistory()
    {
        return $this->hasMany(StatusHistory::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function getRequiredDocuments($status)
    {
        $requiredDocuments = [
            'ATP' => [
                'Purchase Request Form',
                'Project Procurement Management Plan',
                'Annual Procurement Plan',
                'Budget Allocation'
            ],
            'Procurement' => [
                'Technical Specifications',
                'Terms of Reference',
                'Cost Estimate'
            ],
            'Posting in PhilGEPS' => [
                'PhilGEPS Posting Confirmation',
                'Invitation to Bid'
            ],
            'Pre-Bid' => [
                'Pre-Bid Conference Minutes',
                'Pre-Bid Questions and Answers'
            ],
            'Bid Opening' => [
                'Bid Opening Minutes',
                'Bid Evaluation Report'
            ],
            'Bid Evaluation' => [
                'Technical Evaluation Report',
                'Financial Evaluation Report'
            ],
            'Post Qualification' => [
                'Post Qualification Report',
                'Notice of Post Qualification'
            ],
            'Confirmation on Approval' => [
                'Approval Document',
                'Resolution'
            ],
            'Issuance of Notice of Award' => [
                'Notice of Award',
                'Performance Security'
            ],
            'Purchase Order' => [
                'Purchase Order',
                'Delivery Schedule'
            ],
            'Contract' => [
                'Contract Agreement',
                'Contract Signing Minutes'
            ],
            'Notice to Proceed' => [
                'Notice to Proceed',
                'Project Timeline'
            ],
            'Posting of Award in PhilGEPS' => [
                'PhilGEPS Award Posting Confirmation'
            ],
            'Forward Purchase or Supply' => [
                'Delivery Receipt',
                'Inspection Report'
            ]
        ];

        return $requiredDocuments[$status] ?? [];
    }

    /**
     * Get the workflow statuses dynamically from the Process model.
     */
    public static function getWorkflowStatuses()
    {
        return \App\Models\Process::orderBy('order')->pluck('name')->toArray();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_request_id',
        'status',
        'remarks',
        'user_id',
        'started_at',
        'completed_at',
        'is_skipped',
        'step_order',
        'required_documents_completed'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'is_skipped' => 'boolean',
        'required_documents_completed' => 'boolean'
    ];

    public function purchaseRequest()
    {
        return $this->belongsTo(PurchaseRequest::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getDurationAttribute()
    {
        if (!$this->started_at || !$this->completed_at) {
            return null;
        }
        return $this->completed_at->diffInHours($this->started_at);
    }

    public function getProgressPercentageAttribute()
    {
        if ($this->is_skipped) {
            return 100;
        }
        
        if (!$this->started_at) {
            return 0;
        }

        if ($this->completed_at) {
            return 100;
        }

        // Calculate progress based on required documents
        $requiredDocs = $this->purchaseRequest->getRequiredDocuments($this->status);
        if (empty($requiredDocs)) {
            return 50; // If no documents required, assume 50% progress when started
        }

        $uploadedDocs = $this->purchaseRequest->documents()
            ->where('status', $this->status)
            ->count();

        return ($uploadedDocs / count($requiredDocs)) * 100;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'filename',
        'original_filename',
        'path',
        'status',
        'mime_type',
        'file_size',
        'uploaded_by',
        'purchase_request_id',
        'approval_status',
        'rejection_reason',
        'reviewed_by',
        'reviewed_at'
    ];

    public function purchaseRequest()
    {
        return $this->belongsTo(PurchaseRequest::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Check if document is pending approval
     */
    public function isPending()
    {
        return $this->approval_status === 'pending';
    }

    /**
     * Check if document is approved
     */
    public function isApproved()
    {
        return $this->approval_status === 'approved';
    }

    /**
     * Check if document is rejected
     */
    public function isRejected()
    {
        return $this->approval_status === 'rejected';
    }

    /**
     * Approve the document
     */
    public function approve($reviewerId = null)
    {
        $this->update([
            'approval_status' => 'approved',
            'reviewed_by' => $reviewerId ?? auth()->id(),
            'reviewed_at' => now(),
            'rejection_reason' => null
        ]);
    }

    /**
     * Reject the document
     */
    public function reject($reason, $reviewerId = null)
    {
        $this->update([
            'approval_status' => 'rejected',
            'rejection_reason' => $reason,
            'reviewed_by' => $reviewerId ?? auth()->id(),
            'reviewed_at' => now()
        ]);
    }

    /**
     * Check if the document can be viewed in the browser
     */
    public function canBeViewedInBrowser()
    {
        // If path is null, we can't view it
        if (!$this->path) {
            return false;
        }

        $viewableTypes = [
            'application/pdf',
            'text/plain',
            'text/html',
            'text/css',
            'text/javascript',
            'application/json',
            'image/jpeg',
            'image/jpg',
            'image/png',
            'image/gif',
            'image/webp',
            'image/svg+xml',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // .docx
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // .xlsx
            'application/vnd.openxmlformats-officedocument.presentationml.presentation', // .pptx
        ];

        // If MIME type is available, check it first
        if ($this->mime_type && in_array($this->mime_type, $viewableTypes)) {
            return true;
        }

        // Fallback to extension-based detection
        $extension = strtolower($this->getExtension());
        $viewableExtensions = [
            'pdf', 'txt', 'html', 'htm', 'css', 'js', 'json',
            'jpg', 'jpeg', 'png', 'gif', 'webp', 'svg',
            'docx', 'xlsx', 'pptx'
        ];

        return in_array($extension, $viewableExtensions);
    }

    /**
     * Get the file extension
     */
    public function getExtension()
    {
        return pathinfo($this->original_filename, PATHINFO_EXTENSION);
    }

    /**
     * Get the file type icon class
     */
    public function getFileTypeIcon()
    {
        $extension = strtolower($this->getExtension());
        
        switch ($extension) {
            case 'pdf':
                return 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z';
            case 'doc':
            case 'docx':
                return 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z';
            case 'xls':
            case 'xlsx':
                return 'M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z';
            case 'ppt':
            case 'pptx':
                return 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z';
            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'gif':
            case 'webp':
                return 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z';
            case 'txt':
                return 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z';
            default:
                return 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z';
        }
    }
}

<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\PurchaseRequest;
use App\Models\Document;

class DocumentUploaded extends Notification implements ShouldQueue
{
    use Queueable;

    protected $purchaseRequest;
    protected $document;

    public function __construct(PurchaseRequest $purchaseRequest, Document $document)
    {
        $this->purchaseRequest = $purchaseRequest;
        $this->document = $document;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Document Uploaded - ' . $this->purchaseRequest->pr_number)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('A new document has been uploaded for a purchase request.')
            ->line('**Document Details:**')
            ->line('• PR Number: ' . $this->purchaseRequest->pr_number)
            ->line('• PR Name: ' . $this->purchaseRequest->name)
            ->line('• Document: ' . $this->document->original_filename)
            ->line('• Status: ' . $this->document->status)
            ->line('• Uploaded by: ' . auth()->user()->name)
            ->line('• Uploaded at: ' . $this->document->created_at->format('F j, Y g:i A'))
            ->line('• File size: ' . number_format($this->document->file_size / 1024, 2) . ' KB')
            ->action('View Purchase Request', route('purchase-requests.show', $this->purchaseRequest))
            ->line('Please review the uploaded document and take appropriate action.')
            ->line('Thank you for using the BAC System!');
    }

    public function toArray($notifiable)
    {
        return [
            'purchase_request_id' => $this->purchaseRequest->id,
            'purchase_request_number' => $this->purchaseRequest->pr_number,
            'purchase_request_name' => $this->purchaseRequest->name,
            'document_id' => $this->document->id,
            'document_name' => $this->document->original_filename,
            'status' => $this->document->status,
            'uploaded_by' => auth()->user()->name,
            'uploaded_at' => $this->document->created_at,
            'file_size' => $this->document->file_size,
            'message' => 'New document "' . $this->document->original_filename . '" uploaded for ' . $this->purchaseRequest->name,
            'action_url' => route('purchase-requests.show', $this->purchaseRequest)
        ];
    }
} 
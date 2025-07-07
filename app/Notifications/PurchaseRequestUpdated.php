<?php

namespace App\Notifications;

use App\Models\PurchaseRequest;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class PurchaseRequestUpdated extends Notification
{
    protected $purchaseRequest;
    protected $oldStatus;
    protected $action;

    /**
     * Create a new notification instance.
     */
    public function __construct(PurchaseRequest $purchaseRequest, string $oldStatus = null, string $action = 'updated')
    {
        $this->purchaseRequest = $purchaseRequest;
        $this->oldStatus = $oldStatus;
        $this->action = $action;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $title = match ($this->action) {
            'created' => 'New Purchase Request Created',
            'updated' => 'Purchase Request Status Updated',
            'type_changed' => 'Purchase Request Type Changed',
            'status_advanced' => 'Purchase Request Workflow Advanced',
            'step_skipped' => 'Purchase Request Step Skipped',
            'step_removed' => 'Purchase Request Step Removed',
            'step_added' => 'Purchase Request Step Added',
            'workflow_reset' => 'Purchase Request Workflow Reset',
            default   => 'Purchase Request Update',
        };

        $message = match ($this->action) {
            'created' => "A new purchase request '{$this->purchaseRequest->name}' has been created.",
            'updated' => "The status of purchase request '{$this->purchaseRequest->name}' has been updated from '{$this->oldStatus}' to '{$this->purchaseRequest->status}'.",
            'type_changed' => "The type of purchase request '{$this->purchaseRequest->name}' has been changed from '{$this->oldStatus}' to '{$this->purchaseRequest->type}'.",
            'status_advanced' => "Purchase request '{$this->purchaseRequest->name}' workflow has been advanced from '{$this->oldStatus}' to '{$this->purchaseRequest->status}'.",
            'step_skipped' => "Step '{$this->oldStatus}' has been skipped for purchase request '{$this->purchaseRequest->name}'.",
            'step_removed' => "Step '{$this->oldStatus}' has been removed from purchase request '{$this->purchaseRequest->name}' workflow.",
            'step_added' => "Step '{$this->oldStatus}' has been added to purchase request '{$this->purchaseRequest->name}' workflow.",
            'workflow_reset' => "Purchase request '{$this->purchaseRequest->name}' workflow has been reset to default steps.",
            default   => "The purchase request '{$this->purchaseRequest->name}' has been {$this->action}.",
        };

        $updater = $this->purchaseRequest->lastModifiedBy ? $this->purchaseRequest->lastModifiedBy->name : 'N/A';
        
        // Handle the updated_at date properly
        $updatedAt = now()->format('F j, Y g:i A');
        if ($this->purchaseRequest->last_modified_at) {
            $updatedAtValue = $this->purchaseRequest->last_modified_at;
            if (is_string($updatedAtValue)) {
                try {
                    $updatedAtValue = \Carbon\Carbon::parse($updatedAtValue);
                } catch (\Exception $e) {
                    $updatedAtValue = now();
                }
            }
            $updatedAt = $updatedAtValue->format('F j, Y g:i A');
        } elseif ($this->purchaseRequest->updated_at) {
            $updatedAtValue = $this->purchaseRequest->updated_at;
            if (is_string($updatedAtValue)) {
                try {
                    $updatedAtValue = \Carbon\Carbon::parse($updatedAtValue);
                } catch (\Exception $e) {
                    $updatedAtValue = now();
                }
            }
            $updatedAt = $updatedAtValue->format('F j, Y g:i A');
        }

        return (new MailMessage)
            ->subject($title)
            ->markdown('emails.purchase-request-update', [
                'title' => $title,
                'message' => $message,
                'purchaseRequest' => $this->purchaseRequest,
                'oldStatus' => $this->oldStatus,
                'notifiable' => $notifiable,
                'updater' => $updater,
                'updatedAt' => $updatedAt,
            ]);
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'purchase_request_id' => $this->purchaseRequest->id,
            'action' => $this->action,
            'old_status' => $this->oldStatus,
            'new_status' => $this->purchaseRequest->status,
            'message' => match ($this->action) {
                'created' => "New purchase request '{$this->purchaseRequest->name}' has been created.",
                'updated' => "Purchase request '{$this->purchaseRequest->name}' status updated from '{$this->oldStatus}' to '{$this->purchaseRequest->status}'.",
                'type_changed' => "Purchase request '{$this->purchaseRequest->name}' type changed from '{$this->oldStatus}' to '{$this->purchaseRequest->type}'.",
                'status_advanced' => "Purchase request '{$this->purchaseRequest->name}' has been status_advanced.",
                'step_skipped' => "Purchase request '{$this->purchaseRequest->name}' has been step_skipped.",
                'step_removed' => "Purchase request '{$this->purchaseRequest->name}' has been step_removed.",
                'step_added' => "Purchase request '{$this->purchaseRequest->name}' has been step_added.",
                'workflow_reset' => "Purchase request '{$this->purchaseRequest->name}' has been workflow_reset.",
                default   => "Purchase request '{$this->purchaseRequest->name}' has been {$this->action}.",
            },
        ];
    }
}
<?php

namespace App\Notifications;

use App\Models\PurchaseRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewPurchaseRequestCreated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $purchaseRequest;

    public function __construct(PurchaseRequest $purchaseRequest)
    {
        $this->purchaseRequest = $purchaseRequest;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Purchase Request Created - ' . $this->purchaseRequest->pr_number)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('A new purchase request has been created and requires your attention.')
            ->line('**PR Details:**')
            ->line('• PR Number: ' . $this->purchaseRequest->pr_number)
            ->line('• Name: ' . $this->purchaseRequest->name)
            ->line('• Type: ' . ucfirst($this->purchaseRequest->type))
            ->line('• Department: ' . $this->purchaseRequest->department->name)
            ->line('• Created by: ' . $this->purchaseRequest->user->name)
            ->line('• Created at: ' . $this->purchaseRequest->created_at->format('F j, Y g:i A'))
            ->action('Review Purchase Request', route('purchase-requests.show', $this->purchaseRequest))
            ->line('Please review this purchase request and take appropriate action.')
            ->line('Thank you for using the BAC System!');
    }

    public function toArray($notifiable)
    {
        return [
            'purchase_request_id' => $this->purchaseRequest->id,
            'purchase_request_number' => $this->purchaseRequest->pr_number,
            'purchase_request_name' => $this->purchaseRequest->name,
            'type' => $this->purchaseRequest->type,
            'department' => $this->purchaseRequest->department->name,
            'created_by' => $this->purchaseRequest->user->name,
            'created_at' => $this->purchaseRequest->created_at,
            'message' => 'New purchase request "' . $this->purchaseRequest->name . '" created by ' . $this->purchaseRequest->user->name,
            'action_url' => route('purchase-requests.show', $this->purchaseRequest)
        ];
    }
} 
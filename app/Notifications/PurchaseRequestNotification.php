<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\PurchaseRequest;

class PurchaseRequestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $purchaseRequest;
    protected $action;
    protected $message;

    public function __construct(PurchaseRequest $purchaseRequest, string $action, string $message)
    {
        $this->purchaseRequest = $purchaseRequest;
        $this->action = $action;
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject("Purchase Request {$this->action}")
            ->line($this->message)
            ->line("PR Number: {$this->purchaseRequest->pr_number}")
            ->line("Status: {$this->purchaseRequest->status}")
            ->action('View Purchase Request', route('purchase-requests.show', $this->purchaseRequest))
            ->line('Thank you for using the BAC System!');
    }

    public function toArray($notifiable)
    {
        return [
            'purchase_request_id' => $this->purchaseRequest->id,
            'pr_number' => $this->purchaseRequest->pr_number,
            'action' => $this->action,
            'message' => $this->message,
            'status' => $this->purchaseRequest->status
        ];
    }
} 
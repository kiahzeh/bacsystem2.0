<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Welcome to the BAC System')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Welcome to the BAC System.')
            ->line('You can now create and manage purchase requests, track their status, and collaborate with your team.')
            ->action('Go to Dashboard', route('dashboard'))
            ->line('Thank you for using the BAC System!');
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'Welcome to the Purchase Request System!',
            'action_url' => route('dashboard'),
            'action_text' => 'Go to Dashboard'
        ];
    }
} 
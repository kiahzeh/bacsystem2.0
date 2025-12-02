<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewUserRegistered extends Notification
{
    use Queueable;

    public function __construct(public User $newUser)
    {
    }

    public function via($notifiable)
    {
        // Keep database for in-app dropdown; mail optional based on env
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New user registered')
            ->greeting('Hello ' . ($notifiable->name ?? 'Admin'))
            ->line('A new user has registered and is pending approval:')
            ->line('Name: ' . $this->newUser->name)
            ->line('Email: ' . $this->newUser->email)
            ->action('Review Users', route('users.index'))
            ->line('You can approve the account from the Users page.');
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'New user registered',
            'message' => sprintf('New account: %s (%s). Pending admin approval.', $this->newUser->name, $this->newUser->email),
            'user_id' => $this->newUser->id,
            'action_url' => route('users.index'),
            'action_text' => 'Review Users',
        ];
    }
}


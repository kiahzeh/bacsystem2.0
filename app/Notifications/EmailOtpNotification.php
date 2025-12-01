<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class EmailOtpNotification extends Notification
{
    use Queueable;

    public function __construct(
        public readonly string $code,
        public readonly int $ttlMinutes
    ) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your Verification Code')
            ->greeting('Hello '.$notifiable->name)
            ->line('Use the verification code below to verify your email address:')
            ->line('Verification Code: '.$this->code)
            ->line('This code expires in '.$this->ttlMinutes.' minutes.')
            ->line('If you did not attempt to sign up, you can ignore this email.');
    }
}
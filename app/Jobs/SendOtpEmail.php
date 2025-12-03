<?php

namespace App\Jobs;

use App\Notifications\EmailOtpNotification;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendOtpEmail
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public readonly User $user,
        public readonly string $code,
        public readonly int $ttlMinutes
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $this->user->notify(new EmailOtpNotification($this->code, $this->ttlMinutes));
        } catch (\Throwable $e) {
            report($e);
        }
    }
}


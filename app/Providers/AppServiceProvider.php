<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        // Ensure admin can log in by auto-verifying and approving the seeded admin
        try {
            if (Schema::hasTable('users')) {
                $adminEmail = env('ADMIN_EMAIL');
                if ($adminEmail) {
                    $admin = User::where('email', $adminEmail)->first();
                    if ($admin) {
                        $needsVerify = !$admin->email_verified_at;
                        $needsApprove = !(bool)($admin->is_approved ?? false);
                        $isAdmin = (bool)($admin->is_admin ?? false) || ($admin->role ?? null) === 'admin';
                        if ($isAdmin && ($needsVerify || $needsApprove)) {
                            $admin->forceFill([
                                'email_verified_at' => $admin->email_verified_at ?: now(),
                                'is_approved' => true,
                            ])->save();
                        }
                    }
                }
            }
        } catch (\Throwable $e) {
            // swallow
        }
    }
}

<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use App\Models\User;
use App\Models\Department;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

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

        // Ensure admin can log in by auto-creating (if missing), verifying, and approving the seeded admin
        try {
            if (Schema::hasTable('users')) {
                $adminEmail = config('app.admin_email');
                error_log('[AdminBootstrap] admin_email='.($adminEmail ?: 'NULL'));
                if ($adminEmail) {
                    $admin = User::where('email', $adminEmail)->first();

                    // If admin user does not exist, create it using environment values
                    if (! $admin) {
                        $adminPassword = (string) config('app.admin_password');
                        $adminName = (string) config('app.admin_name');

                        // Attempt to assign Admin department if available; otherwise any department; else null
                        $deptId = null;
                        if (Schema::hasTable('departments')) {
                            $deptId = Department::where('name', 'Admin')->value('id')
                                ?? Department::value('id');
                        }

                        $values = [
                            'name' => $adminName,
                            'email' => $adminEmail,
                            'password' => Hash::make($adminPassword),
                            'department_id' => $deptId,
                            'email_verified_at' => now(),
                        ];

                        // Conditionally set columns if they exist
                        if (Schema::hasColumn('users', 'role')) {
                            $values['role'] = 'admin';
                        }
                        if (Schema::hasColumn('users', 'is_admin')) {
                            $values['is_admin'] = true;
                        }
                        if (Schema::hasColumn('users', 'is_approved')) {
                            $values['is_approved'] = true;
                        }

                        $admin = User::create($values);
                        error_log('[AdminBootstrap] created admin user for '.$adminEmail);
                    }

                    // If admin exists, ensure verified and approved to prevent lockout
                    if ($admin) {
                        // Optionally force-reset admin credentials and privileges on boot
                        $forceReset = filter_var(config('app.admin_reset_password_on_boot', false), FILTER_VALIDATE_BOOLEAN);
                        if ($forceReset) {
                            $resetPatch = [
                                'password' => Hash::make((string) config('app.admin_password')),
                                'email_verified_at' => $admin->email_verified_at ?: now(),
                            ];
                            if (Schema::hasColumn('users', 'role')) {
                                $resetPatch['role'] = 'admin';
                            }
                            if (Schema::hasColumn('users', 'is_admin')) {
                                $resetPatch['is_admin'] = true;
                            }
                            if (Schema::hasColumn('users', 'is_approved')) {
                                $resetPatch['is_approved'] = true;
                            }
                            $admin->forceFill($resetPatch)->save();
                            error_log('[AdminBootstrap] reset admin password/flags for '.$adminEmail);
                        }

                        $needsVerify = ! $admin->email_verified_at;
                        $needsApprove = ! (bool) ($admin->is_approved ?? false);
                        $isAdmin = (bool) ($admin->is_admin ?? false) || (($admin->role ?? null) === 'admin');
                        if ($isAdmin && ($needsVerify || $needsApprove)) {
                            $patch = [
                                'email_verified_at' => $admin->email_verified_at ?: now(),
                            ];
                            if (Schema::hasColumn('users', 'is_approved')) {
                                $patch['is_approved'] = true;
                            }
                            $admin->forceFill($patch)->save();
                            error_log('[AdminBootstrap] ensured admin verified/approved for '.$adminEmail);
                        }
                    }
                }
            }
        } catch (\Throwable $e) {
            error_log('[AdminBootstrap] ERROR: '.($e->getMessage()));
        }
    }
}

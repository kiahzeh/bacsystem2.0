<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_approved')->default(false)->after('is_admin');
        });

        // Auto-approve existing admin users to prevent lockout
        try {
            DB::table('users')
                ->where(function ($q) {
                    $q->where('role', 'admin')
                      ->orWhere('is_admin', 1);
                })
                ->update(['is_approved' => 1]);
        } catch (\Throwable $e) {
            // Swallow errors to avoid migration failure on fresh installs
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_approved');
        });
    }
};
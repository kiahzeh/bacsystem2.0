<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add missing columns to status_histories if they don't exist
        if (!Schema::hasColumn('status_histories', 'started_at')) {
            Schema::table('status_histories', function (Blueprint $table) {
                $table->timestamp('started_at')->nullable();
            });
        }

        if (!Schema::hasColumn('status_histories', 'completed_at')) {
            Schema::table('status_histories', function (Blueprint $table) {
                $table->timestamp('completed_at')->nullable();
            });
        }

        if (!Schema::hasColumn('status_histories', 'is_skipped')) {
            Schema::table('status_histories', function (Blueprint $table) {
                $table->boolean('is_skipped')->default(false);
            });
        }

        if (!Schema::hasColumn('status_histories', 'step_order')) {
            Schema::table('status_histories', function (Blueprint $table) {
                $table->integer('step_order')->nullable();
            });
        }

        if (!Schema::hasColumn('status_histories', 'required_documents_completed')) {
            Schema::table('status_histories', function (Blueprint $table) {
                $table->boolean('required_documents_completed')->default(false);
            });
        }

        // Ensure 'remarks' exists (fresh start migration used 'notes')
        if (!Schema::hasColumn('status_histories', 'remarks')) {
            Schema::table('status_histories', function (Blueprint $table) {
                $table->text('remarks')->nullable();
            });
        }
    }

    public function down(): void
    {
        // Drop added columns if they exist
        if (Schema::hasColumn('status_histories', 'started_at')) {
            Schema::table('status_histories', function (Blueprint $table) {
                $table->dropColumn('started_at');
            });
        }

        if (Schema::hasColumn('status_histories', 'completed_at')) {
            Schema::table('status_histories', function (Blueprint $table) {
                $table->dropColumn('completed_at');
            });
        }

        if (Schema::hasColumn('status_histories', 'is_skipped')) {
            Schema::table('status_histories', function (Blueprint $table) {
                $table->dropColumn('is_skipped');
            });
        }

        if (Schema::hasColumn('status_histories', 'step_order')) {
            Schema::table('status_histories', function (Blueprint $table) {
                $table->dropColumn('step_order');
            });
        }

        if (Schema::hasColumn('status_histories', 'required_documents_completed')) {
            Schema::table('status_histories', function (Blueprint $table) {
                $table->dropColumn('required_documents_completed');
            });
        }

        if (Schema::hasColumn('status_histories', 'remarks')) {
            Schema::table('status_histories', function (Blueprint $table) {
                $table->dropColumn('remarks');
            });
        }
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Add missing columns expected by the Document model and code
        Schema::table('documents', function (Blueprint $table) {
            if (!Schema::hasColumn('documents', 'filename')) {
                $table->string('filename')->nullable();
            }
            if (!Schema::hasColumn('documents', 'original_filename')) {
                $table->string('original_filename')->nullable();
            }
            if (!Schema::hasColumn('documents', 'path')) {
                $table->string('path')->nullable();
            }
            if (!Schema::hasColumn('documents', 'status')) {
                $table->string('status')->nullable();
            }
            if (!Schema::hasColumn('documents', 'approval_status')) {
                $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending');
            }
            if (!Schema::hasColumn('documents', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable();
            }
            if (!Schema::hasColumn('documents', 'reviewed_by')) {
                $table->unsignedBigInteger('reviewed_by')->nullable();
            }
            if (!Schema::hasColumn('documents', 'reviewed_at')) {
                $table->timestamp('reviewed_at')->nullable();
            }
        });

        // Backfill data from legacy columns if present
        if (Schema::hasColumn('documents', 'name')) {
            DB::statement("UPDATE documents SET original_filename = name WHERE original_filename IS NULL OR original_filename = ''");
        }
        if (Schema::hasColumn('documents', 'file_path')) {
            DB::statement("UPDATE documents SET path = file_path WHERE path IS NULL OR path = ''");
        }
        if (Schema::hasColumn('documents', 'is_approved')) {
            DB::statement("UPDATE documents SET approval_status = CASE WHEN is_approved = 1 THEN 'approved' ELSE COALESCE(approval_status, 'pending') END");
        }
        if (Schema::hasColumn('documents', 'approved_at')) {
            DB::statement('UPDATE documents SET reviewed_at = approved_at WHERE reviewed_at IS NULL');
        }

        // Add foreign key for reviewed_by if column exists and users table is present
        Schema::table('documents', function (Blueprint $table) {
            // SQLite does not support adding foreign constraints easily; skip if SQLite
            // Leave reviewed_by as a nullable unsignedBigInteger without FK in local dev
        });

        // Optionally drop legacy columns to avoid confusion (SQLite requires one drop per table call)
        if (Schema::hasColumn('documents', 'name')) {
            Schema::table('documents', function (Blueprint $table) {
                $table->dropColumn('name');
            });
        }
        if (Schema::hasColumn('documents', 'file_path')) {
            Schema::table('documents', function (Blueprint $table) {
                $table->dropColumn('file_path');
            });
        }
        if (Schema::hasColumn('documents', 'is_approved')) {
            Schema::table('documents', function (Blueprint $table) {
                $table->dropColumn('is_approved');
            });
        }
        if (Schema::hasColumn('documents', 'approved_at')) {
            Schema::table('documents', function (Blueprint $table) {
                $table->dropColumn('approved_at');
            });
        }
    }

    public function down(): void
    {
        // Reverse: re-add legacy columns and attempt to copy back
        Schema::table('documents', function (Blueprint $table) {
            if (!Schema::hasColumn('documents', 'name')) {
                $table->string('name')->nullable();
            }
            if (!Schema::hasColumn('documents', 'file_path')) {
                $table->string('file_path')->nullable();
            }
            if (!Schema::hasColumn('documents', 'is_approved')) {
                $table->boolean('is_approved')->default(false);
            }
            if (!Schema::hasColumn('documents', 'approved_at')) {
                $table->timestamp('approved_at')->nullable();
            }
        });

        // Copy back
        DB::statement('UPDATE documents SET name = original_filename WHERE name IS NULL');
        DB::statement('UPDATE documents SET file_path = path WHERE file_path IS NULL');

        // Drop new columns added (one drop per call for SQLite)
        foreach (['filename','original_filename','path','status','approval_status','rejection_reason','reviewed_by','reviewed_at'] as $col) {
            if (Schema::hasColumn('documents', $col)) {
                Schema::table('documents', function (Blueprint $table) use ($col) {
                    $table->dropColumn($col);
                });
            }
        }
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop all tables in correct order to avoid foreign key issues
        $tables = [
            'consolidate_purchase_request',
            'documents',
            'bids',
            'status_histories',
            'audit_logs',
            'notifications',
            'purchase_requests',
            'consolidated_requests',
            'consolidates',
            'processes',
            'users',
            'departments',
            'personal_access_tokens',
            'password_reset_tokens',
            'failed_jobs'
        ];

        foreach ($tables as $table) {
            try {
                Schema::dropIfExists($table);
            } catch (\Exception $e) {
                // Table might not exist, continue
            }
        }

        // Create departments first (no dependencies)
        if (!Schema::hasTable('departments')) {
            Schema::create('departments', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        // Create users table
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->string('role')->default('user');
                $table->boolean('is_admin')->default(false);
                $table->foreignId('department_id')->constrained()->onDelete('cascade');
                $table->rememberToken();
                $table->timestamps();
            });
        }

        // Create processes table
        if (!Schema::hasTable('processes')) {
            Schema::create('processes', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('description')->nullable();
                $table->boolean('requires_document')->default(false);
                $table->timestamps();
            });
        }

        // Create consolidated_requests table
        if (!Schema::hasTable('consolidated_requests')) {
            Schema::create('consolidated_requests', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('description')->nullable();
                $table->string('status')->default('pending');
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->timestamps();
            });
        }

        // Create purchase_requests table
        if (!Schema::hasTable('purchase_requests')) {
            Schema::create('purchase_requests', function (Blueprint $table) {
                $table->id();
                $table->string('pr_number');
                $table->string('name');
                $table->date('order_date');
                $table->string('status')->default('ATP');
                $table->text('remarks')->nullable();
                $table->string('funding')->nullable();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('department_id')->constrained()->onDelete('cascade');
                $table->unsignedBigInteger('consolidated_request_id')->nullable();
                $table->json('workflow_steps')->nullable();
                $table->boolean('is_archived')->default(false);
                $table->timestamp('archived_at')->nullable();
                $table->timestamps();
            });
        }

        // Add foreign key constraint for consolidated_request_id
        if (Schema::hasTable('purchase_requests') && Schema::hasTable('consolidated_requests')) {
            Schema::table('purchase_requests', function (Blueprint $table) {
                $table->foreign('consolidated_request_id')->references('id')->on('consolidated_requests')->onDelete('cascade');
            });
        }

        // Create documents table
        if (!Schema::hasTable('documents')) {
            Schema::create('documents', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('file_path');
                $table->string('mime_type')->nullable();
                $table->bigInteger('file_size')->nullable();
                $table->foreignId('purchase_request_id')->constrained()->onDelete('cascade');
                $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
                $table->boolean('is_approved')->default(false);
                $table->timestamp('approved_at')->nullable();
                $table->timestamps();
            });
        }

        // Create consolidates table
        if (!Schema::hasTable('consolidates')) {
            Schema::create('consolidates', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('description')->nullable();
                $table->string('status')->default('pending');
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->json('pr_numbers')->nullable();
                $table->timestamps();
            });
        }

        // Create other essential tables
        if (!Schema::hasTable('bids')) {
            Schema::create('bids', function (Blueprint $table) {
                $table->id();
                $table->string('vendor_name');
                $table->decimal('amount', 10, 2);
                $table->foreignId('purchase_request_id')->constrained()->onDelete('cascade');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('audit_logs')) {
            Schema::create('audit_logs', function (Blueprint $table) {
                $table->id();
                $table->string('action');
                $table->text('description')->nullable();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->morphs('auditable');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('status_histories')) {
            Schema::create('status_histories', function (Blueprint $table) {
                $table->id();
                $table->string('status');
                $table->text('notes')->nullable();
                $table->foreignId('purchase_request_id')->constrained()->onDelete('cascade');
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('notifications')) {
            Schema::create('notifications', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('type');
                $table->morphs('notifiable');
                $table->text('data');
                $table->timestamp('read_at')->nullable();
                $table->timestamps();
            });
        }

        // Create pivot table
        if (!Schema::hasTable('consolidate_purchase_request')) {
            Schema::create('consolidate_purchase_request', function (Blueprint $table) {
                $table->id();
                $table->foreignId('consolidate_id')->constrained()->onDelete('cascade');
                $table->foreignId('purchase_request_id')->constrained()->onDelete('cascade');
                $table->timestamps();
            });
        }

        // Create Laravel default tables
        if (!Schema::hasTable('password_reset_tokens')) {
            Schema::create('password_reset_tokens', function (Blueprint $table) {
                $table->string('email')->primary();
                $table->string('token');
                $table->timestamp('created_at')->nullable();
            });
        }

        if (!Schema::hasTable('personal_access_tokens')) {
            Schema::create('personal_access_tokens', function (Blueprint $table) {
                $table->id();
                $table->morphs('tokenable');
                $table->string('name');
                $table->string('token', 64)->unique();
                $table->text('abilities')->nullable();
                $table->timestamp('last_used_at')->nullable();
                $table->timestamp('expires_at')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('failed_jobs')) {
            Schema::create('failed_jobs', function (Blueprint $table) {
                $table->id();
                $table->string('uuid')->unique();
                $table->text('connection');
                $table->text('queue');
                $table->longText('payload');
                $table->longText('exception');
                $table->timestamp('failed_at')->useCurrent();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop all tables
        $tables = [
            'consolidate_purchase_request',
            'documents',
            'bids',
            'status_histories',
            'audit_logs',
            'notifications',
            'purchase_requests',
            'consolidated_requests',
            'consolidates',
            'processes',
            'users',
            'departments',
            'personal_access_tokens',
            'password_reset_tokens',
            'failed_jobs'
        ];

        foreach ($tables as $table) {
            Schema::dropIfExists($table);
        }
    }
};

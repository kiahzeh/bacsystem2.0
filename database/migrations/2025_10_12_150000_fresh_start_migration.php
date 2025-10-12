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
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Create users table
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

        // Create processes table
        Schema::create('processes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('requires_document')->default(false);
            $table->timestamps();
        });

        // Create consolidated_requests table
        Schema::create('consolidated_requests', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('status')->default('pending');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        // Create purchase_requests table
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

        // Add foreign key constraint for consolidated_request_id
        Schema::table('purchase_requests', function (Blueprint $table) {
            $table->foreign('consolidated_request_id')->references('id')->on('consolidated_requests')->onDelete('cascade');
        });

        // Create documents table
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

        // Create consolidates table
        Schema::create('consolidates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('status')->default('pending');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->json('pr_numbers')->nullable();
            $table->timestamps();
        });

        // Create other essential tables
        Schema::create('bids', function (Blueprint $table) {
            $table->id();
            $table->string('vendor_name');
            $table->decimal('amount', 10, 2);
            $table->foreignId('purchase_request_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->string('action');
            $table->text('description')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->morphs('auditable');
            $table->timestamps();
        });

        Schema::create('status_histories', function (Blueprint $table) {
            $table->id();
            $table->string('status');
            $table->text('notes')->nullable();
            $table->foreignId('purchase_request_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        // Create pivot table
        Schema::create('consolidate_purchase_request', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consolidate_id')->constrained()->onDelete('cascade');
            $table->foreignId('purchase_request_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        // Create Laravel default tables
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

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

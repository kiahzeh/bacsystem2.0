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
    public function up()
    {
        // If the purchase_requests table already exists, skip this destructive recreation
        if (Schema::hasTable('purchase_requests')) {
            return;
        }

        // Drop foreign key constraints using raw SQL with error handling
        try {
            DB::statement('ALTER TABLE documents DROP CONSTRAINT IF EXISTS documents_purchase_request_id_foreign');
        } catch (\Exception $e) {
            // Table might not exist, continue
        }
        
        try {
            DB::statement('ALTER TABLE consolidate_purchase_request DROP CONSTRAINT IF EXISTS consolidate_purchase_request_purchase_request_id_foreign');
        } catch (\Exception $e) {
            // Table might not exist, continue
        }
        
        try {
            DB::statement('ALTER TABLE purchase_requests DROP CONSTRAINT IF EXISTS purchase_requests_consolidated_request_id_foreign');
        } catch (\Exception $e) {
            // Table might not exist, continue
        }

        // Drop the existing table if it exists (safe because we checked above)
        Schema::dropIfExists('purchase_requests');

        // Recreate the table with the new schema (including foreign keys and other necessary columns)
        Schema::create('purchase_requests', function (Blueprint $table) {
            $table->id();
            $table->string('pr_number');  // The purchase request number
            $table->string('name');  // Add the 'name' column
            $table->foreignId('department_id')->constrained()->onDelete('cascade');  // Foreign key for department
            $table->string('status');  // The status of the purchase request
            $table->string('type');  // The type of purchase request
            $table->string('remarks')->nullable();  // Optional remarks field
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');  // Foreign key for user
            $table->timestamps();  // Timestamps for created_at and updated_at
        });
        
        // Recreate foreign key constraints
        if (Schema::hasTable('documents')) {
            Schema::table('documents', function (Blueprint $table) {
                $table->foreign('purchase_request_id')->references('id')->on('purchase_requests')->onDelete('cascade');
            });
        }
        
        // Only recreate constraint if table exists
        if (Schema::hasTable('consolidate_purchase_request')) {
            Schema::table('consolidate_purchase_request', function (Blueprint $table) {
                $table->foreign('purchase_request_id')->references('id')->on('purchase_requests')->onDelete('cascade');
            });
        }
    }

    public function down()
    {
        // Drop the table when rolling back
        Schema::dropIfExists('purchase_requests');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Guard against duplicate creation if the table already exists
        if (Schema::hasTable('purchase_requests')) {
            return;
        }

        Schema::create('purchase_requests', function (Blueprint $table) {
            $table->id();
            $table->string('pr_number')->unique();
            $table->string('name'); // Short name/title for the PR
            $table->string('project_title');
            $table->date('order_date');
            $table->string('mode_of_procurement');
            $table->decimal('abc_approved_budget', 15, 2);
            $table->string('category');
            $table->text('purpose_description');
            $table->string('status')->default('ATP');
            $table->text('remarks')->nullable();
            $table->string('funding')->nullable();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('department_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('consolidated_request_id')->nullable();

            // Workflow & Concurrency
            $table->json('workflow_steps')->nullable();
            $table->json('enabled_steps')->nullable();
            $table->unsignedInteger('version')->default(1);
            $table->timestamp('last_modified_at')->nullable();
            $table->foreignId('last_modified_by')->nullable()->constrained('users')->onDelete('set null');

            // Completion Fields
            $table->date('completion_date')->nullable();
            $table->decimal('final_amount', 15, 2)->nullable();
            $table->string('awarded_vendor')->nullable();
            $table->string('contract_number')->nullable();
            $table->text('completion_notes')->nullable();

            // Archiving
            $table->boolean('is_archived')->default(false);
            $table->timestamp('archived_at')->nullable();
            $table->foreignId('archived_by')->nullable()->constrained('users')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_requests');
    }
};

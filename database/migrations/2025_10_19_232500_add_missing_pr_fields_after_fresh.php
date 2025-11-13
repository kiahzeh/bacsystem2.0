<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('purchase_requests')) {
            Schema::table('purchase_requests', function (Blueprint $table) {
                if (!Schema::hasColumn('purchase_requests', 'project_title')) {
                    $table->string('project_title')->nullable();
                }
                if (!Schema::hasColumn('purchase_requests', 'mode_of_procurement')) {
                    $table->string('mode_of_procurement')->nullable();
                }
                if (!Schema::hasColumn('purchase_requests', 'abc_approved_budget')) {
                    $table->decimal('abc_approved_budget', 15, 2)->nullable();
                }
                if (!Schema::hasColumn('purchase_requests', 'category')) {
                    $table->string('category')->nullable();
                }
                if (!Schema::hasColumn('purchase_requests', 'purpose_description')) {
                    $table->text('purpose_description')->nullable();
                }
                if (!Schema::hasColumn('purchase_requests', 'completion_date')) {
                    $table->date('completion_date')->nullable();
                }
                if (!Schema::hasColumn('purchase_requests', 'final_amount')) {
                    $table->decimal('final_amount', 15, 2)->nullable();
                }
                if (!Schema::hasColumn('purchase_requests', 'awarded_vendor')) {
                    $table->string('awarded_vendor')->nullable();
                }
                if (!Schema::hasColumn('purchase_requests', 'contract_number')) {
                    $table->string('contract_number')->nullable();
                }
                if (!Schema::hasColumn('purchase_requests', 'completion_notes')) {
                    $table->text('completion_notes')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('purchase_requests')) {
            Schema::table('purchase_requests', function (Blueprint $table) {
                if (Schema::hasColumn('purchase_requests', 'project_title')) {
                    $table->dropColumn('project_title');
                }
                if (Schema::hasColumn('purchase_requests', 'mode_of_procurement')) {
                    $table->dropColumn('mode_of_procurement');
                }
                if (Schema::hasColumn('purchase_requests', 'abc_approved_budget')) {
                    $table->dropColumn('abc_approved_budget');
                }
                if (Schema::hasColumn('purchase_requests', 'category')) {
                    $table->dropColumn('category');
                }
                if (Schema::hasColumn('purchase_requests', 'purpose_description')) {
                    $table->dropColumn('purpose_description');
                }
                if (Schema::hasColumn('purchase_requests', 'completion_date')) {
                    $table->dropColumn('completion_date');
                }
                if (Schema::hasColumn('purchase_requests', 'final_amount')) {
                    $table->dropColumn('final_amount');
                }
                if (Schema::hasColumn('purchase_requests', 'awarded_vendor')) {
                    $table->dropColumn('awarded_vendor');
                }
                if (Schema::hasColumn('purchase_requests', 'contract_number')) {
                    $table->dropColumn('contract_number');
                }
                if (Schema::hasColumn('purchase_requests', 'completion_notes')) {
                    $table->dropColumn('completion_notes');
                }
            });
        }
    }
};
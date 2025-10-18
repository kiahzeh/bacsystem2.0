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
        $hasCompletionDate = Schema::hasColumn('purchase_requests', 'completion_date');
        $hasFinalAmount = Schema::hasColumn('purchase_requests', 'final_amount');
        $hasAwardedVendor = Schema::hasColumn('purchase_requests', 'awarded_vendor');
        $hasContractNumber = Schema::hasColumn('purchase_requests', 'contract_number');
        $hasCompletionNotes = Schema::hasColumn('purchase_requests', 'completion_notes');

        Schema::table('purchase_requests', function (Blueprint $table) use ($hasCompletionDate, $hasFinalAmount, $hasAwardedVendor, $hasContractNumber, $hasCompletionNotes) {
            if (!$hasCompletionDate) {
                $table->date('completion_date')->nullable();
            }
            if (!$hasFinalAmount) {
                $table->decimal('final_amount', 15, 2)->nullable();
            }
            if (!$hasAwardedVendor) {
                $table->string('awarded_vendor')->nullable();
            }
            if (!$hasContractNumber) {
                $table->string('contract_number')->nullable();
            }
            if (!$hasCompletionNotes) {
                $table->text('completion_notes')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_requests', function (Blueprint $table) {
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
};

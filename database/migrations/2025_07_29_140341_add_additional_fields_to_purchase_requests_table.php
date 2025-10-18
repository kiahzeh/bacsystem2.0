<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        $hasProjectTitle = Schema::hasColumn('purchase_requests', 'project_title');
        $hasMode = Schema::hasColumn('purchase_requests', 'mode_of_procurement');
        $hasAbc = Schema::hasColumn('purchase_requests', 'abc_approved_budget');
        $hasCategory = Schema::hasColumn('purchase_requests', 'category');
        $hasPurpose = Schema::hasColumn('purchase_requests', 'purpose_description');

        Schema::table('purchase_requests', function (Blueprint $table) use ($hasProjectTitle, $hasMode, $hasAbc, $hasCategory, $hasPurpose) {
            if (!$hasProjectTitle) {
                $table->string('project_title')->nullable();
            }
            if (!$hasMode) {
                $table->string('mode_of_procurement')->nullable();
            }
            if (!$hasAbc) {
                $table->decimal('abc_approved_budget', 15, 2)->nullable();
            }
            if (!$hasCategory) {
                $table->string('category')->nullable();
            }
            if (!$hasPurpose) {
                $table->text('purpose_description')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
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
        });
    }
};

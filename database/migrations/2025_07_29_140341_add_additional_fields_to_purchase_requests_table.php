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
        Schema::table('purchase_requests', function (Blueprint $table) {
            $table->string('project_title')->nullable(); // Project title
            $table->string('mode_of_procurement')->nullable(); // Mode of procurement
            $table->decimal('abc_approved_budget', 15, 2)->nullable(); // ABC/Approved Budget for Contract
            $table->string('category')->nullable(); // Category
            $table->text('purpose_description')->nullable(); // Purpose/Description
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('purchase_requests', function (Blueprint $table) {
            $table->dropColumn([
                'project_title',
                'mode_of_procurement',
                'abc_approved_budget',
                'category',
                'purpose_description'
            ]);
        });
    }
};

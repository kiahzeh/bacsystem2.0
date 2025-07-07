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
            // Check if the column 'pr_number' already exists
            if (!Schema::hasColumn('purchase_requests', 'pr_number')) {
                $table->string('pr_number');
            }
        });
    }
    
    public function down()
    {
        Schema::table('purchase_requests', function (Blueprint $table) {
            $table->dropColumn('pr_number');
        });
    }
    
};

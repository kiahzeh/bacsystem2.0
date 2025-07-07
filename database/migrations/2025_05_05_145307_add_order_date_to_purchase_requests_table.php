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
            $table->dateTime('order_date');  // Adding the 'order_date' column as a datetime type
        });
    }
    
    public function down()
    {
        Schema::table('purchase_requests', function (Blueprint $table) {
            $table->dropColumn('order_date');  // Drop the 'order_date' column if we rollback the migration
        });
    }
    
};

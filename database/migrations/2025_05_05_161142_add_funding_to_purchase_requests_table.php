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
        $table->string('funding')->nullable();  // Add funding column
    });
}

public function down()
{
    Schema::table('purchase_requests', function (Blueprint $table) {
        $table->dropColumn('funding');  // Drop funding column if rollback
    });
}

};

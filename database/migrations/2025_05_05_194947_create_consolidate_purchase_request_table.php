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
    Schema::create('consolidate_purchase_request', function (Blueprint $table) {
        $table->id();
        $table->foreignId('consolidate_id')->constrained()->onDelete('cascade');
        $table->foreignId('pr_id')->constrained('purchase_requests')->onDelete('cascade');
        $table->timestamps();
    });
}
    
    public function down()
    {
        Schema::dropIfExists('consolidate_purchase_request');
    }
    
    

};

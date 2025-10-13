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
            $table->string('name');
            $table->date('order_date');
            $table->string('department');
            $table->string('status')->default('ATP');
            $table->text('remarks')->nullable();
            $table->foreignId('user_id')->constrained(); // Reference to the user who created the request
            $table->string('pr_number'); // Original PR number
            $table->unsignedBigInteger('consolidated_request_id')->nullable();
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

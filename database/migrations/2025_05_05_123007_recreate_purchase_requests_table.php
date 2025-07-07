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
        // Drop the existing table if it exists (for SQLite workaround)
        Schema::dropIfExists('purchase_requests');

        // Recreate the table with the new schema (including foreign keys and other necessary columns)
        Schema::create('purchase_requests', function (Blueprint $table) {
            $table->id();
            $table->string('pr_number');  // The purchase request number
            $table->string('name');  // Add the 'name' column
            $table->foreignId('department_id')->constrained()->onDelete('cascade');  // Foreign key for department
            $table->string('status');  // The status of the purchase request
            $table->string('type');  // The type of purchase request
            $table->string('remarks')->nullable();  // Optional remarks field
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');  // Foreign key for user
            $table->timestamps();  // Timestamps for created_at and updated_at
        });
    }

    public function down()
    {
        // Drop the table when rolling back
        Schema::dropIfExists('purchase_requests');
    }
};

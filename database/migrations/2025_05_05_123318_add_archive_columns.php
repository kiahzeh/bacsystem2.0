<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Add archive columns to purchase_requests table
        Schema::table('purchase_requests', function (Blueprint $table) {
            $table->boolean('is_archived')->default(false);
            $table->timestamp('archived_at')->nullable();
            $table->foreignId('archived_by')->nullable()->constrained('users');
        });

        // Add archive columns to documents table
        Schema::table('documents', function (Blueprint $table) {
            $table->boolean('is_archived')->default(false);
            $table->timestamp('archived_at')->nullable();
            $table->foreignId('archived_by')->nullable()->constrained('users');
        });

        // Add archive columns to consolidate table
        Schema::table('consolidates', function (Blueprint $table) {
            $table->boolean('is_archived')->default(false);
            $table->timestamp('archived_at')->nullable();
            $table->foreignId('archived_by')->nullable()->constrained('users');
        });
    }

    public function down()
    {
        Schema::table('purchase_requests', function (Blueprint $table) {
            $table->dropColumn(['is_archived', 'archived_at', 'archived_by']);
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn(['is_archived', 'archived_at', 'archived_by']);
        });

        Schema::table('consolidates', function (Blueprint $table) {
            $table->dropColumn(['is_archived', 'archived_at', 'archived_by']);
        });
    }
}; 
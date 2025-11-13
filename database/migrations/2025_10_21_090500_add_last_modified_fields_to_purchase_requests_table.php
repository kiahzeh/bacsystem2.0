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
        Schema::table('purchase_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('purchase_requests', 'last_modified_by')) {
                $table->foreignId('last_modified_by')->nullable()->constrained('users');
            }
            if (!Schema::hasColumn('purchase_requests', 'last_modified_at')) {
                $table->timestamp('last_modified_at')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_requests', function (Blueprint $table) {
            if (Schema::hasColumn('purchase_requests', 'last_modified_by')) {
                try {
                    $table->dropForeign(['last_modified_by']);
                } catch (\Throwable $e) {
                    // Ignore if FK doesn't exist or SQLite limitations
                }
                $table->dropColumn('last_modified_by');
            }
            if (Schema::hasColumn('purchase_requests', 'last_modified_at')) {
                $table->dropColumn('last_modified_at');
            }
        });
    }
};
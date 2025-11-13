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
        // Add missing 'order' column if it does not exist
        if (!Schema::hasColumn('processes', 'order')) {
            Schema::table('processes', function (Blueprint $table) {
                $table->integer('order')->default(0)->after('name');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('processes', 'order')) {
            Schema::table('processes', function (Blueprint $table) {
                $table->dropColumn('order');
            });
        }
    }
};
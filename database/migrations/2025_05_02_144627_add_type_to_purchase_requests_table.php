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
        if (!Schema::hasColumn('purchase_requests', 'type')) {
            Schema::table('purchase_requests', function (Blueprint $table) {
                $table->enum('type', ['alternative', 'competitive'])
                      ->default('alternative')
                      ->after('status');
            });
        }
    }
    
    public function down(): void
    {
        if (Schema::hasColumn('purchase_requests', 'type')) {
            Schema::table('purchase_requests', function (Blueprint $table) {
                $table->dropColumn('type');
            });
        }
    }
    

};

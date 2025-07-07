<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('purchase_requests', function (Blueprint $table) {
            $table->unsignedInteger('version')->default(1);
            $table->timestamp('last_modified_at')->nullable();
            $table->foreignId('last_modified_by')->nullable()->constrained('users');
            $table->boolean('is_locked')->default(false);
            $table->foreignId('locked_by')->nullable()->constrained('users');
            $table->timestamp('locked_at')->nullable();
        });
    }

    public function down()
    {
        Schema::table('purchase_requests', function (Blueprint $table) {
            $table->dropColumn([
                'version',
                'last_modified_at',
                'last_modified_by',
                'is_locked',
                'locked_by',
                'locked_at'
            ]);
        });
    }
}; 
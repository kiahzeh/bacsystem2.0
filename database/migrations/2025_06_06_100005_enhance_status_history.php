<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('status_histories', function (Blueprint $table) {
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->boolean('is_skipped')->default(false);
            $table->integer('step_order')->nullable();
            $table->boolean('required_documents_completed')->default(false);
        });
    }

    public function down()
    {
        Schema::table('status_histories', function (Blueprint $table) {
            $table->dropColumn(['started_at', 'completed_at', 'is_skipped', 'step_order', 'required_documents_completed']);
        });
    }
}; 
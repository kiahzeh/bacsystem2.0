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
        // If notifications table doesn't exist, create with Laravel defaults
        if (!Schema::hasTable('notifications')) {
            Schema::create('notifications', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('type');
                $table->morphs('notifiable');
                $table->text('data');
                $table->timestamp('read_at')->nullable();
                $table->timestamps();
            });
            return;
        }

        // If the table exists but lacks notifiable columns, rebuild to correct schema
        $missingNotifiableType = !Schema::hasColumn('notifications', 'notifiable_type');
        $missingNotifiableId = !Schema::hasColumn('notifications', 'notifiable_id');

        if ($missingNotifiableType || $missingNotifiableId) {
            // Drop and recreate with proper schema (data loss acceptable for dev)
            Schema::dropIfExists('notifications');
            Schema::create('notifications', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('type');
                $table->morphs('notifiable');
                $table->text('data');
                $table->timestamp('read_at')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert by dropping table; original incorrect schema isn't restored
        Schema::dropIfExists('notifications');
    }
};
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Guard against duplicate creation if the table already exists
        if (Schema::hasTable('processes')) {
            return;
        }

        Schema::create('processes', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('processes');
    }
};
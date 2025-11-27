<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // First, copy data from file_path to path if path is empty
        DB::statement("UPDATE documents SET path = file_path WHERE path IS NULL OR path = ''");

        // Drop file_path column
        Schema::table('documents', function (Blueprint $table) {
            if (Schema::hasColumn('documents', 'file_path')) {
                $table->dropColumn('file_path');
            }
        });
    }

    public function down()
    {
        Schema::table('documents', function (Blueprint $table) {
            if (!Schema::hasColumn('documents', 'file_path')) {
                $table->string('file_path')->after('path');
            }
        });

        // Copy data back if needed
        DB::statement("UPDATE documents SET file_path = path WHERE file_path IS NULL OR file_path = ''");
    }
};
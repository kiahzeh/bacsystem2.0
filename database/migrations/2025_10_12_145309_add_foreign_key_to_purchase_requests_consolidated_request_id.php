<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Only add FK if the column and target table exist and FK is missing
        if (Schema::hasColumn('purchase_requests', 'consolidated_request_id') && Schema::hasTable('consolidated_requests')) {
            $fkName = 'purchase_requests_consolidated_request_id_foreign';
            $hasFk = collect(DB::select(
                "SELECT tc.constraint_name FROM information_schema.table_constraints tc WHERE tc.table_name = ? AND tc.constraint_type = 'FOREIGN KEY'",
                ['purchase_requests']
            ))->contains(function ($row) use ($fkName) {
                return $row->constraint_name === $fkName;
            });

            if (!$hasFk) {
                Schema::table('purchase_requests', function (Blueprint $table) {
                    $table->foreign('consolidated_request_id')
                        ->references('id')
                        ->on('consolidated_requests')
                        ->onDelete('cascade');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop FK if present; ignore if already dropped
        try {
            Schema::table('purchase_requests', function (Blueprint $table) {
                $table->dropForeign(['consolidated_request_id']);
            });
        } catch (\Throwable $e) {}
    }
};

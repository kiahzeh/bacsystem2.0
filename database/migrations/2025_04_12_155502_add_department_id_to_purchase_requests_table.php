<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add the column if missing; otherwise only add the FK if missing
        if (!Schema::hasColumn('purchase_requests', 'department_id')) {
            Schema::table('purchase_requests', function (Blueprint $table) {
                $table->foreignId('department_id')->nullable()->constrained();
            });
            return;
        }

        // If the column exists, ensure the FK constraint exists
        $fkName = 'purchase_requests_department_id_foreign';
        $hasFk = collect(DB::select(
            "SELECT tc.constraint_name FROM information_schema.table_constraints tc WHERE tc.table_name = ? AND tc.constraint_type = 'FOREIGN KEY'",
            ['purchase_requests']
        ))->contains(function ($row) use ($fkName) {
            return $row->constraint_name === $fkName;
        });

        if (!$hasFk) {
            Schema::table('purchase_requests', function (Blueprint $table) {
                $table->foreign('department_id')->references('id')->on('departments');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('purchase_requests', 'department_id')) {
            // Drop FK if present (ignore errors if already dropped)
            try {
                Schema::table('purchase_requests', function (Blueprint $table) {
                    $table->dropForeign(['department_id']);
                });
            } catch (\Throwable $e) {}

            Schema::table('purchase_requests', function (Blueprint $table) {
                $table->dropColumn('department_id');
            });
        }
    }
};

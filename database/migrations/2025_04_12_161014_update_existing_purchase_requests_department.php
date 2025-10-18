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
        // If the legacy 'department' column is already removed, skip this migration
        if (!Schema::hasColumn('purchase_requests', 'department')) {
            return;
        }
        // First, make sure we have a default department
        $defaultDept = DB::table('departments')->first();
        if (!$defaultDept) {
            $defaultDept = DB::table('departments')->insertGetId([
                'name' => 'General',
                'description' => 'Default Department',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $defaultDept = $defaultDept->id;
        }

        // Update existing purchase requests
        $purchaseRequests = DB::table('purchase_requests')->get();
        foreach ($purchaseRequests as $pr) {
            // Try to find a department with matching name
            $department = DB::table('departments')
                ->where('name', $pr->department)
                ->first();

            $departmentId = $department ? $department->id : $defaultDept;

            DB::table('purchase_requests')
                ->where('id', $pr->id)
                ->update([
                        'department_id' => $departmentId,
                    ]);
        }

        // Now we can safely drop the old department column if it still exists
        if (Schema::hasColumn('purchase_requests', 'department')) {
            Schema::table('purchase_requests', function (Blueprint $table) {
                $table->dropColumn('department');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasColumn('purchase_requests', 'department')) {
            Schema::table('purchase_requests', function (Blueprint $table) {
                $table->string('department')->nullable();
            });
        }

        // Restore the department names from department_id
        $purchaseRequests = DB::table('purchase_requests')->get();
        foreach ($purchaseRequests as $pr) {
            $department = DB::table('departments')
                ->where('id', $pr->department_id)
                ->first();

            if ($department) {
                DB::table('purchase_requests')
                    ->where('id', $pr->id)
                    ->update([
                            'department' => $department->name,
                        ]);
            }
        }
    }
};

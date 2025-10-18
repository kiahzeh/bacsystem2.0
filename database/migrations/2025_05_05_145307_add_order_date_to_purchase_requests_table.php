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
    public function up()
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'pgsql') {
            // Idempotent add for Postgres and normalize type
            DB::statement('ALTER TABLE purchase_requests ADD COLUMN IF NOT EXISTS order_date DATE');
            try {
                $rows = DB::select(
                    "SELECT data_type FROM information_schema.columns WHERE table_name = ? AND column_name = ?",
                    ['purchase_requests', 'order_date']
                );
                if (!empty($rows)) {
                    $dataType = $rows[0]->data_type ?? null;
                    if ($dataType === 'timestamp without time zone') {
                        DB::statement('ALTER TABLE purchase_requests ALTER COLUMN order_date TYPE date USING order_date::date');
                    }
                }
            } catch (\Throwable $e) {
                // Ignore type normalization errors
            }
            return;
        }

        // Non-Postgres: guard with Schema check
        $hasOrderDate = Schema::hasColumn('purchase_requests', 'order_date');
        if ($hasOrderDate) {
            return;
        }

        Schema::table('purchase_requests', function (Blueprint $table) {
            $table->date('order_date')->nullable();
        });
    }
    
    public function down()
    {
        if (Schema::hasColumn('purchase_requests', 'order_date')) {
            Schema::table('purchase_requests', function (Blueprint $table) {
                $table->dropColumn('order_date');
            });
        }
    }
    
};

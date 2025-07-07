<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('purchase_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('purchase_requests', 'workflow_steps')) {
                $table->json('workflow_steps')->nullable();
            }
            if (!Schema::hasColumn('purchase_requests', 'current_step_index')) {
                $table->integer('current_step_index')->default(0);
            }
            if (!Schema::hasColumn('purchase_requests', 'is_workflow_customized')) {
                $table->boolean('is_workflow_customized')->default(false);
            }
        });
    }

    public function down()
    {
        Schema::table('purchase_requests', function (Blueprint $table) {
            $columns = ['workflow_steps', 'current_step_index', 'is_workflow_customized'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('purchase_requests', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
}; 
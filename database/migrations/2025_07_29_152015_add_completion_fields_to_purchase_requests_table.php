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
        Schema::table('purchase_requests', function (Blueprint $table) {
            $table->date('completion_date')->nullable()->after('remarks');
            $table->decimal('final_amount', 15, 2)->nullable()->after('completion_date');
            $table->string('awarded_vendor')->nullable()->after('final_amount');
            $table->string('contract_number')->nullable()->after('awarded_vendor');
            $table->text('completion_notes')->nullable()->after('contract_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_requests', function (Blueprint $table) {
            $table->dropColumn([
                'completion_date',
                'final_amount',
                'awarded_vendor',
                'contract_number',
                'completion_notes'
            ]);
        });
    }
};

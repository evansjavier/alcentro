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
        Schema::table('expense_concepts', function (Blueprint $table) {
            $table->unsignedInteger('billing_period_months')->nullable()->after('is_billable')->comment('Periodo sugerido en meses para cobrar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expense_concepts', function (Blueprint $table) {
            $table->dropColumn('billing_period_months');
        });
    }
};

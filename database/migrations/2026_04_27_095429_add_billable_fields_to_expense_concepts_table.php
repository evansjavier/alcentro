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
            $table->boolean('is_billable')->default(false)->after('name');
            $table->string('type')->nullable()->after('is_billable')->comment('service, admin, maintenance, etc.');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expense_concepts', function (Blueprint $table) {
            $table->dropColumn(['is_billable', 'type']);
        });
    }
};

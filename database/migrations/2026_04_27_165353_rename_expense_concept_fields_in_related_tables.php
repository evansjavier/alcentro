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
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropForeign(['expense_concept_id']);
            $table->renameColumn('expense_concept_id', 'concept_id');
        });

        Schema::table('expenses', function (Blueprint $table) {
            $table->foreign('concept_id')->references('id')->on('concepts')->cascadeOnDelete();
        });

        Schema::table('invoice_items', function (Blueprint $table) {
            $table->dropForeign(['expense_concept_id']);
            $table->renameColumn('expense_concept_id', 'concept_id');
        });

        Schema::table('invoice_items', function (Blueprint $table) {
            $table->foreign('concept_id')->references('id')->on('concepts')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_items', function (Blueprint $table) {
            $table->dropForeign(['concept_id']);
            $table->renameColumn('concept_id', 'expense_concept_id');
        });

        Schema::table('invoice_items', function (Blueprint $table) {
            $table->foreign('expense_concept_id')->references('id')->on('expense_concepts')->nullOnDelete();
        });

        Schema::table('expenses', function (Blueprint $table) {
            $table->dropForeign(['concept_id']);
            $table->renameColumn('concept_id', 'expense_concept_id');
        });

        Schema::table('expenses', function (Blueprint $table) {
            $table->foreign('expense_concept_id')->references('id')->on('expense_concepts')->cascadeOnDelete();
        });
    }
};

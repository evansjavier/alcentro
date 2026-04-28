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
        Schema::table('invoice_items', function (Blueprint $table) {
            $table->decimal('amount', 12, 2)->nullable()->change();
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->enum('document_status', ['draft', 'issued', 'cancelled'])->default('draft')->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('document_status');
        });

        Schema::table('invoice_items', function (Blueprint $table) {
            $table->decimal('amount', 12, 2)->nullable(false)->change();
        });
    }
};

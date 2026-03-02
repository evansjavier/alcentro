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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('premise_id')->constrained();
            $table->decimal('rent_amount', 12, 2);
            $table->integer('payment_day')->default(5); // Billing cycle date
            $table->decimal('maintenance_pct', 5, 2)->default(10.00);
            $table->decimal('advertising_pct', 5, 2)->default(10.00);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->enum('status', ['activo', 'pendiente_firma', 'finalizado', 'rescindido'])->default('activo');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};

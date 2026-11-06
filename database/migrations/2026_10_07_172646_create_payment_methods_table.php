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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type')->comment('pago_movil, transferencia, binance_pay, zelle, otro');
            $table->string('bank')->nullable();
            $table->string('tax_id')->nullable()->comment('RIF / DNI del titular');
            $table->string('account_number')->nullable()->comment('Número de cuenta o teléfono');
            $table->string('account_holder')->nullable()->comment('Titular de la cuenta');
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};

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
        Schema::create('income_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->string('membership_slug');
            $table->string('membership_name');
            $table->decimal('amount_usd', 10, 2);
            $table->decimal('amount_ves', 12, 2);
            $table->decimal('bcv_rate', 12, 4);
            $table->foreignId('payment_method_id')->nullable()->constrained('payment_methods')->nullOnDelete();
            $table->string('payment_method_name');
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->string('bank_reference');
            $table->date('payment_date');
            $table->string('account_holder_name')->comment('Titular de la cuenta origen');
            $table->string('account_holder_relationship')->default('propia')->comment('propia o tercero');
            $table->string('proof_path')->nullable()->comment('Comprobante/captura de pago');
            $table->string('status')->default('pendiente')->comment('pendiente, aprobado, rechazado');
            $table->timestamp('processed_at')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index('payment_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('income_notifications');
    }
};

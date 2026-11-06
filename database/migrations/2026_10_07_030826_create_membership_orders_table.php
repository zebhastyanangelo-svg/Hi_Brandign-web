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
        Schema::create('membership_orders', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->string('membership_slug');
            $table->string('membership_name');
            $table->unsignedInteger('membership_price');
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('tax_id');
            $table->string('country');
            $table->string('city')->nullable();
            $table->string('address');
            $table->string('payment_method');
            $table->string('card_last4', 4)->nullable();
            $table->string('paypal_email')->nullable();
            $table->string('transfer_reference')->nullable();
            $table->string('discount_code')->nullable();
            $table->decimal('subtotal', 10, 2);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('tax_amount', 10, 2);
            $table->decimal('total', 10, 2);
            $table->string('status')->default('procesando');
            $table->timestamp('paid_at')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('membership_orders');
    }
};

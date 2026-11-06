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
        if (! Schema::hasTable('event_payment_method')) {
            Schema::create('event_payment_method', function (Blueprint $table) {
                $table->id();
                $table->foreignId('event_id')->constrained()->cascadeOnDelete();
                $table->foreignId('payment_method_id')->constrained()->cascadeOnDelete();
                $table->timestamps();

                $table->unique(['event_id', 'payment_method_id']);
            });
        }

        Schema::table('bookings', function (Blueprint $table) {
            if (! Schema::hasColumn('bookings', 'payment_method_id')) {
                $table->foreignId('payment_method_id')->nullable()->after('proof_path')->constrained('payment_methods')->nullOnDelete();
            }
        });

        Schema::table('events', function (Blueprint $table) {
            if (Schema::hasColumn('events', 'bank_details')) {
                $table->dropColumn('bank_details');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            if (! Schema::hasColumn('events', 'bank_details')) {
                $table->json('bank_details')->nullable()->after('image');
            }
        });

        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'payment_method_id')) {
                $table->dropConstrainedForeignId('payment_method_id');
            }
        });

        Schema::dropIfExists('event_payment_method');
    }
};

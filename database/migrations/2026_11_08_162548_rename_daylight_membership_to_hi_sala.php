<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('memberships')) {
            if (DB::table('memberships')->where('slug', 'hi-sala')->exists()) {
                DB::table('memberships')->where('slug', 'daylight')->delete();
            } else {
                DB::table('memberships')
                    ->where('slug', 'daylight')
                    ->update([
                        'slug' => 'hi-sala',
                        'name' => 'Hi sala',
                        'updated_at' => now(),
                    ]);
            }
        }

        foreach (['membership_orders', 'income_notifications'] as $table) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            DB::table($table)
                ->where('membership_slug', 'daylight')
                ->update(['membership_slug' => 'hi-sala']);

            DB::table($table)
                ->where('membership_name', 'Daylight')
                ->update(['membership_name' => 'Hi sala']);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('memberships')) {
            DB::table('memberships')
                ->where('slug', 'hi-sala')
                ->update([
                    'slug' => 'daylight',
                    'name' => 'Daylight',
                    'updated_at' => now(),
                ]);
        }

        foreach (['membership_orders', 'income_notifications'] as $table) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            DB::table($table)
                ->where('membership_slug', 'hi-sala')
                ->update(['membership_slug' => 'daylight']);

            DB::table($table)
                ->where('membership_name', 'Hi sala')
                ->update(['membership_name' => 'Daylight']);
        }
    }
};

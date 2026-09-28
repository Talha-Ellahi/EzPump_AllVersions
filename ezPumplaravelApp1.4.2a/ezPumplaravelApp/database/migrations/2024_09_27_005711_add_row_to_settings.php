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
        if (!Schema::hasTable('settings')) {

            DB::table('settings')->insert([
                [
                    'key' => 'shift_min_close_duration',
                    'value' => '60',
                    'description' => 'Minimum Time required to close the shift ( in minutes)',
                    'type' => 'number',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'key' => 'last_date_seen',
                    'value' => now()->toDateTimeString(),
                    'description' => 'Last date accessed',
                    'type' => 'datetime',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            //
        });
    }
};

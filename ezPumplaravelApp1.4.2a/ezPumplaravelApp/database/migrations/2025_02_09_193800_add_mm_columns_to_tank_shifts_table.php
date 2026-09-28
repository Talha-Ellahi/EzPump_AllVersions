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
        Schema::table('tank_shifts', function (Blueprint $table) {
            $table->unsignedInteger('opening_mm')->default(0)->after('manual_closing_totalizer');
            $table->unsignedInteger('closing_mm')->default(0)->after('opening_mm');
            $table->unsignedInteger('manual_opening_mm')->nullable()->after('closing_mm');
            $table->unsignedInteger('manual_closing_mm')->nullable()->after('manual_opening_mm');
        });
        Schema::table('tank_shift_logs', function (Blueprint $table) {
            $table->unsignedInteger('opening_mm')->default(0)->after('manual_closing_totalizer');
            $table->unsignedInteger('closing_mm')->default(0)->after('opening_mm');
            $table->unsignedInteger('manual_opening_mm')->nullable()->after('closing_mm');
            $table->unsignedInteger('manual_closing_mm')->nullable()->after('manual_opening_mm');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tank_shifts', function (Blueprint $table) {
            $table->dropColumn([
                'opening_mm',
                'closing_mm',
                'manual_opening_mm',
                'manual_closing_mm'
            ]);
        });
    }
};

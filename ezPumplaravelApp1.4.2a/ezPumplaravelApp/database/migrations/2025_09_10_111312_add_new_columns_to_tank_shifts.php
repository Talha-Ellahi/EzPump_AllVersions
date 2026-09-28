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

            if (!Schema::hasColumn('tank_shifts', 'opening_mm')) {
                $table->unsignedInteger('opening_mm')->default(0)->after('manual_closing_totalizer');
            }
            if (!Schema::hasColumn('tank_shifts', 'closing_mm')) {
                $table->unsignedInteger('closing_mm')->default(0)->after('opening_mm');
            }
            if (!Schema::hasColumn('tank_shifts', 'manual_opening_mm')) {
                $table->unsignedInteger('manual_opening_mm')->default(0)->after('opening_mm');
            }
            if (!Schema::hasColumn('tank_shifts', 'manual_closing_mm')) {
                $table->unsignedInteger('manual_closing_mm')->default(0)->after('manual_opening_mm');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tank_shifts', function (Blueprint $table) {
            if (Schema::hasColumn('tank_shifts', 'closing_mm')) {
                $table->dropColumn('closing_mm');
            }
            if (Schema::hasColumn('tank_shifts', 'opening_mm')) {
                $table->dropColumn('opening_mm');
            }
            if (!Schema::hasColumn('tank_shifts', 'manual_opening_mm')) {
                $table->dropColumn('manual_opening_mm');
            }
            if (!Schema::hasColumn('tank_shifts', 'manual_closing_mm')) {
                $table->dropColumn('manual_closing_mm');
            }

        });
    }
};

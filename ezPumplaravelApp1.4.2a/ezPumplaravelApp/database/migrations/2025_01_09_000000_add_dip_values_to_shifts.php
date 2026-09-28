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
        Schema::table('shift', function (Blueprint $table) {
//            $table->decimal('manual_opening_dip', 8, 2)->nullable()->after('closing_fuel_manual');
//            $table->decimal('manual_closing_dip', 8, 2)->nullable()->after('manual_opening_dip');
//            $table->unsignedBigInteger('tank_id')->nullable()->after('manual_closing_dip');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shifts', function (Blueprint $table) {
            $table->dropColumn(['manual_opening_dip', 'manual_closing_dip', 'tank_id']);
        });
    }
};

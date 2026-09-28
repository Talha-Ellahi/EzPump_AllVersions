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
        Schema::table('SysConfig', function (Blueprint $table) {
            if (!Schema::hasColumn('SysConfig', 'NoofShifts')) {
                $table->tinyInteger('NoofShifts')->default(1);
            }
            if (!Schema::hasColumn('SysConfig', 'Shift1Code')) {
                $table->tinyInteger('Shift1Code')->default(6);
            }
            if (!Schema::hasColumn('SysConfig', 'Shift2Code')) {
                $table->tinyInteger('Shift2Code')->default(0);
            }
            if (!Schema::hasColumn('SysConfig', 'Shift3Code')) {
                $table->tinyInteger('Shift3Code')->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('SysConfig', function (Blueprint $table) {
            if (Schema::hasColumn('SysConfig', 'NoofShifts')) {
                $table->dropColumn('NoofShifts');
            }
            if (Schema::hasColumn('SysConfig', 'Shift1Code')) {
                $table->dropColumn('Shift1Code');
            }
            if (Schema::hasColumn('SysConfig', 'Shift2Code')) {
                $table->dropColumn('Shift2Code');
            }
            if (Schema::hasColumn('SysConfig', 'Shift3Code')) {
                $table->dropColumn('Shift3Code');
            }
        });
    }
};

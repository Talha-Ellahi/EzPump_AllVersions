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
        if (!Schema::hasColumn('shift', 'shiftTimer')) {
            Schema::table('shift', function (Blueprint $table) {
                $table->time('shiftTimer')->default('00:00:00')->nullable(true);
            });
        }
        if (!Schema::hasColumn('shiftsend', 'shiftTimer')) {
            Schema::table('shiftsend', function (Blueprint $table) {
                $table->time('shiftTimer')->default('00:00:00')->nullable(true);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shift', function (Blueprint $table) {
            $table->dropColumn('shiftTimer')->default('00:00:00')->nullable(true);
        });
        Schema::table('shiftsend', function (Blueprint $table) {
            $table->dropColumn('shiftTimer');
        });
    }
};

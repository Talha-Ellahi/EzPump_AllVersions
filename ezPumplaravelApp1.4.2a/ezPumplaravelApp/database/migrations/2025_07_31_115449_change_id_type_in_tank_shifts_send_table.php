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
        // Step 1: Fix invalid 'type' values first
        DB::statement("UPDATE `tank_shifts_send` SET `type` = 1 WHERE CAST(`type` AS CHAR) = 'opening'");
        DB::statement("UPDATE `tank_shifts_send` SET `type` = 2 WHERE CAST(`type` AS CHAR) = 'closing'");
        DB::statement("UPDATE `tank_shifts_send` SET `type` = NULL WHERE `type` REGEXP '^[^0-9]+$'");

        // Step 2: Now safely modify columns
        Schema::table('tank_shifts_send', function (Blueprint $table) {
            $table->tinyInteger('type')->change();
        });
    }

    public function down()
    {
        // Optional rollback logic
        Schema::table('tank_shifts_send', function (Blueprint $table) {
            $table->string('type')->change();
        });

    }
};

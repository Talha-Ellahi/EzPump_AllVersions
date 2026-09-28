<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop table if it exists
        DB::statement('DROP TABLE IF EXISTS tank_shifts_send');
        
        // Step 1: Copy the schema of tank_shifts to tank_shifts_send
        DB::statement('CREATE TABLE tank_shifts_send LIKE tank_shifts');


        // Step 2: Modify the id column to be a normal integer (remove AUTO_INCREMENT)
        // Assuming id in tank_shifts is BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
        DB::statement('ALTER TABLE tank_shifts_send MODIFY id BIGINT UNSIGNED NOT NULL');

        // Step 3: Drop the primary key from id
        DB::statement('ALTER TABLE tank_shifts_send DROP PRIMARY KEY');

        // Step 4: Add pid as the new auto-incrementing primary key
        DB::statement('ALTER TABLE tank_shifts_send ADD pid BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY');

        DB::statement('ALTER TABLE tank_shifts_send ADD `type` VARCHAR(255)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the tank_shifts_send table to reverse the migration
        DB::statement('DROP TABLE IF EXISTS tank_shifts_send');
    }
};
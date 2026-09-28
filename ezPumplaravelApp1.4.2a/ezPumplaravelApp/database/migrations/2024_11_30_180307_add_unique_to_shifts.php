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
            $table->unique('pump_id'); // Add a unique index to the pump_id column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shift', function (Blueprint $table) {
            $table->dropUnique(['pump_id']); // Remove the unique index if the migration is rolled back
        });
    }
};

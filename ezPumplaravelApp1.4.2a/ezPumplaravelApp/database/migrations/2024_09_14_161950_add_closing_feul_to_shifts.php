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
        if (!Schema::hasColumn('shift', 'closing_fuel_manual')) {
            Schema::table('shift', function (Blueprint $table) {
                $table->unsignedInteger('closing_fuel_manual')->nullable()->default(0);

            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shift', function (Blueprint $table) {
            //
        });
    }
};

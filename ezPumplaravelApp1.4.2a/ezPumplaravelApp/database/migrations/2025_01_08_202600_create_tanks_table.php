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
        if (!Schema::hasTable('tanks')) {
            Schema::create('tanks', function (Blueprint $table) {
                $table->id();
                $table->string('tank_name');
                $table->string('fuel_type');
                $table->decimal('capacity_liters', 10, 2);
                $table->decimal('temperature', 5, 2);
                $table->decimal('water_height_mm', 10, 2);
                $table->timestamps();
                $table->integer('station_id')->unsigned()->nullable();
                $table->integer('product_id')->unsigned()->nullable();

            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tanks');
    }
};

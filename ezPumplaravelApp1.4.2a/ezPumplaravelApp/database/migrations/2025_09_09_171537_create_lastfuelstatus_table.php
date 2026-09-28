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
        if (!Schema::hasTable('LastFuelStatus')) {
            Schema::create('LastFuelStatus', function (Blueprint $table) {
                $table->id();
                $table->dateTime('tdate')->nullable();
                $table->integer('PAddr')->default(0)->nullable();
                $table->integer('TID')->default(0)->nullable();
                $table->integer('ICode')->default(0)->nullable();
                $table->integer('Level')->default(0)->nullable();
                $table->bigInteger('Qty')->default(0)->nullable(); // Qty ko bigint rakha
                $table->integer('WaterLevel')->default(0)->nullable();
                $table->integer('TEMP')->default(0)->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lastfuelstatus');
    }
};

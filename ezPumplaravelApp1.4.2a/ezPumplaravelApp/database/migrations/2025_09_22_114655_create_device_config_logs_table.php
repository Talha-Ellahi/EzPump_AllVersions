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
        Schema::create('device_config_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dispenser_id')->index(); // kis dispenser ka hai
            $table->string('command')->nullable();              // jo command run hui
            $table->json('csv_data')->nullable();               // parsed RF/POS/Nozzles data JSON
            $table->string('status')->default('pending');       // read/write/error
            $table->boolean('is_active')->default(0);           // latest = 1
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('device_config_logs');
    }
};

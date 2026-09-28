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
        Schema::create('atg_config_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tank_id');
            $table->string('command'); // Read or Write
            $table->json('csv_data')->nullable();
            $table->string('status'); // success or error
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('a_t_g_config_logs');
    }
};

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
        Schema::create('pump_display_names', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('pump_id'); // FC_NZNo
            $table->unsignedInteger('FC_NZNo'); // FC_NZNo
            $table->string('display_name')->nullable(); // User display name
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pump_display_names');
    }
};

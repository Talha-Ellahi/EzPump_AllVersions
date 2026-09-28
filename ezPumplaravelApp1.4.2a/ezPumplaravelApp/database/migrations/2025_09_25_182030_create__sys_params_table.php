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
        Schema::create('SysParams', function (Blueprint $table) {
            $table->id();
            $table->timestamp('date')->nullable();   // system date/time
            $table->string('uptime', 50)->nullable();
            $table->string('cpuload', 50)->nullable();
            $table->string('temp', 50)->nullable();
            $table->string('rom_usage', 100)->nullable();
            $table->string('ram_usage', 100)->nullable();
            $table->string('lan_ip', 255)->nullable();
            $table->string('wan_ip', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('SysParams');
    }
};

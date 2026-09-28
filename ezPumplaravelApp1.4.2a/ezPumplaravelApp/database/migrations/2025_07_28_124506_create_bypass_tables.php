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
        Schema::create('bypass_limit', function (Blueprint $table) {
            $table->id();
            $table->string('sys_id');
            $table->string('month', 7); // Format YYYY-MM
            $table->integer('total_limit');
            $table->timestamps();
        });

        // Table: bypass_record
        Schema::create('bypass_record', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bypass_limit_id')->constrained('bypass_limit')->onDelete('cascade');
            $table->integer('used_limit')->default(0);
            $table->integer('remaining_limit')->default(0);
            $table->string('add_hours')->default(0);
            $table->timestamps();
        });

        // Table: bypass_logs
        Schema::create('bypass_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bypass_limit_id')->constrained('bypass_limit')->onDelete('cascade');
            $table->json('log_data');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bypass_logs');
        Schema::dropIfExists('bypass_record');
        Schema::dropIfExists('bypass_limit');
    }
};

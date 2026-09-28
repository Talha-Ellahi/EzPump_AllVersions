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
        if (!Schema::hasTable('dip_chart_values')) {
            Schema::create('dip_chart_values', function (Blueprint $table) {
                $table->id();
                $table->foreignId('tank_id')->constrained()->onDelete('cascade');
                $table->decimal('millimeter', 10, 2);
                $table->decimal('liter_value', 10, 2);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dip_chart_values');
    }
};

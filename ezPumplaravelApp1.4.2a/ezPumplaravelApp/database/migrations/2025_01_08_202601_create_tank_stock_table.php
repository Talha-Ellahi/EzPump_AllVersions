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
        if (!Schema::hasTable('tank_stock')) {
            Schema::create('tank_stock', function (Blueprint $table) {
                $table->id();
                $table->foreignId('tank_id')->constrained()->onDelete('cascade');
                $table->decimal('stock_value', 10, 2);
                $table->decimal('millimeter', 10, 2);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tank_stock');
    }
};

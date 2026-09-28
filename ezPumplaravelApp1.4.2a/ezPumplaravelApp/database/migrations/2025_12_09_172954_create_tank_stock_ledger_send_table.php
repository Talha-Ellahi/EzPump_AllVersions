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
        Schema::create('tank_stock_ledger_send', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tank_id');
            $table->unsignedBigInteger('shift_id');
            $table->unsignedBigInteger('pump_id');
            $table->string('transaction_type');
            $table->decimal('quantity', 15, 2)->default(0);
            $table->text('comments')->nullable();
            $table->decimal('stock_change', 15, 2)->default(0);
            $table->decimal('millimeter', 10, 2)->default(0);
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tank_stock_ledger_send');
    }
};

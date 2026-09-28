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
        if (!Schema::hasTable('tank_stock_ledger')) {

            Schema::create('tank_stock_ledger', function (Blueprint $table) {
                $table->id();
                $table->foreignId('tank_id')->constrained()->onDelete('cascade');
                $table->string('transaction_type');
                $table->decimal('stock_change', 10, 2);
                $table->decimal('millimeter', 10, 2);
                $table->text('comments')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tank_stock_ledger');
    }
};

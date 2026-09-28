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
        Schema::table('tank_stock_event', function (Blueprint $table) {
            $table->date('invoice_date')->nullable();
            $table->string('vendor_name')->nullable();
            $table->integer('invoice_no',)->nullable();
            $table->string('delivery_or_sap_no')->nullable();
            $table->integer('vehicle_no')->nullable();
            $table->string('driver_name')->nullable();
            $table->string('driver_cell')->nullable();
            $table->string('chemb')->nullable();
            $table->integer('chemb_filling_dip')->nullable();
            $table->integer('chemb_decanting_dip')->nullable();
            $table->integer('seal_no')->nullable();
            $table->decimal('net_amount',15,2)->nullable();
            $table->integer('filling_tmp')->nullable();
            $table->integer('decanting_tmp')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tank_stock_event', function (Blueprint $table) {
            //
        });
    }
};

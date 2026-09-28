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
        if (!Schema::hasTable('shift_payment_wise_sale')) {
            Schema::create('shift_payment_wise_sale', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('shift_id');
                $table->integer('paymentmethod_id');
                $table->decimal('total_sale', 10, 2);
                $table->integer('total_qty');
                $table->decimal('price', 10, 2);

            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shift_payment_wise_sale');

        Schema::table('shift_payment_wise_sale', function (Blueprint $table) {
            $table->foreign('shift_id')->references('id')->on('shift');
            $table->foreign('paymentmethod_id')->references('id')->on('paymentmethod');
        });
    }
};

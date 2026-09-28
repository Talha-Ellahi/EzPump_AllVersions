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
            Schema::table('shift_payment_wise_sale', function (Blueprint $table) {
                $table->unsignedBigInteger('total_sale')->change();
                $table->unsignedBigInteger('total_qty')->change();
                $table->unsignedBigInteger('price')->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shift_payment_wise_sale', function (Blueprint $table) {
            $table->decimal('total_sale', 10, 2)->change();
            $table->integer('total_qty')->change();
            $table->decimal('price', 10, 2)->change();
        });
    }
};

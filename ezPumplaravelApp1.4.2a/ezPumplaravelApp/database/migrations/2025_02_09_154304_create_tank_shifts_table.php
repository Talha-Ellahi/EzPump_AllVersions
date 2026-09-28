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
        if (!Schema::hasTable('tank_shifts')) {
            Schema::create('tank_shifts', function (Blueprint $table) {
                $table->id();
                $table->unsignedbigInteger('tank_id');
//                $table->integer('tank_id');
                $table->integer('product_id');
                $table->unsignedInteger('opening_totalizer')->default(0);
                $table->unsignedInteger('closing_totalizer')->default(0);
                $table->unsignedInteger('manual_opening_totalizer')->nullable();
                $table->unsignedInteger('manual_closing_totalizer')->nullable();
                $table->unsignedInteger('user_id');
                $table->timestamp('start_time')->nullable();
                $table->timestamp('end_time')->nullable();
                $table->boolean('is_modified')->default(false);
                $table->timestamps();
                $table->foreign('tank_id')->references('id')->on('tanks');
                $table->foreign('product_id')->references('ICODE')->on('PRODUCT');
                $table->foreign('user_id')->references('id')->on('users');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tank_shifts');
    }
};

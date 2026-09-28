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
        Schema::create('tank_stock_event', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('tank_id');
            $table->integer('shift_id');
            $table->string('sid')->nullable();
            $table->boolean('stock_type')->default(0)->comment('0 is stock not added and 1 is stock added');
            $table->string('status')->default('pending')->comment('tank_shift_opening:1 tank_shift_closing: 2 tank_stock_change:3');
            $table->integer('stock_change');
            $table->integer('millimeter');
            $table->string('comments')->nullable();
            $table->integer('new_stock_value');
            //close and open column tank shift
            $table->integer('product_id')->nullable();
            $table->integer('opening_totalizer')->nullable();
            $table->integer('closing_totalizer')->nullable();
            $table->integer('opening_mm')->nullable();
            $table->integer('manual_opening_mm')->nullable();
            $table->integer('manual_closing_mm')->nullable();
            $table->unsignedInteger('manual_opening_totalizer')->nullable();
            $table->unsignedInteger('manual_closing_totalizer')->nullable();
            $table->integer('user_id')->nullable();
            $table->boolean('is_modified')->nullable()->default(0);
            $table->timestamp('added_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tank_stock_event');
    }
};

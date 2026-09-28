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
        Schema::create('rate_send', function (Blueprint $table) {
            $table->id();
            $table->integer('ICODE')->nullable();
            $table->string('ITMNAME')->nullable();
            $table->decimal('Previous_Rate', 12, 2)->nullable();
            $table->decimal('New_Rate', 12, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rate_send');
    }
};

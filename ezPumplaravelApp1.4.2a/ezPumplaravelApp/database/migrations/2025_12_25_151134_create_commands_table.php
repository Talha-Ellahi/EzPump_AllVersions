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
        Schema::create('commands', function (Blueprint $table) {
            $table->id();
            $table->string('type', 50)
                ->comment('Command type: 200 = Dispenser, 100 = ATG (Config Write / Config Read / Detect)');
                                 // Config Write / Config Read / Detect
            $table->text('response')->comment('Command response data (JSON or raw response)');                     // Config Write / Config Read / Detect
            $table->boolean('exec')->default(0)->comment('Command execution status: 0 = Pending, 1 = Processing, 2=Completed');        // ATG enabled or not
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commands');
    }
};

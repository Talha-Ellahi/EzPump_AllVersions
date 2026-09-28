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
        Schema::create('vendor', function (Blueprint $table) {
            $table->id();
            $table->string('VNAME', 100)->nullable();
            $table->string('ADRS', 200)->nullable();
            $table->string('PH1', 20)->nullable();
            $table->string('PH2', 20)->nullable();
            $table->string('NTN', 20)->nullable();
            $table->string('GST', 20)->nullable();
            $table->string('CNIC', 20)->nullable();
            $table->string('PAY_TERM', 500)->nullable();
            $table->string('CONT_PERSON', 100)->nullable();
            $table->string('CONT_PH', 20)->nullable();
            $table->string('CREATE_BY', 100)->nullable();
            $table->timestamps();
        });
        Schema::table('tank_stock_event', function (Blueprint $table) {
            $table->unsignedBigInteger('vendor_id')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor');
    }
};

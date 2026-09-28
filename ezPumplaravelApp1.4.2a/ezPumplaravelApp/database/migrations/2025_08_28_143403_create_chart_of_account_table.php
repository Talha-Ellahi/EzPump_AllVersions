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
        Schema::create('chart_of_accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('paymentmethod_id')->nullable()
                ->comment('linked with paymentmethod.id');
            $table->string('AC_NAME')->nullable(); // bank name
            $table->integer('customer_id')->nullable(); // bank name
            $table->string('logo')->nullable();
            $table->string('status')->comment('Active , Inactive')->nullable()->default('Active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
//        Schema::dropIfExists('chart_of_accounts');
    }
};

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
        Schema::create('vendors', function (Blueprint $table) {
            $table->bigIncrements('VENDOR_ID'); // Primary Key
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
            $table->date('CREATE_DT')->nullable();
            $table->string('UPDATE_BY', 100)->nullable();
            $table->date('UPDATE_DT')->nullable();
            $table->unsignedBigInteger('COA_ID')->nullable();
            $table->integer('CODE')->nullable();

//            $table->primary('VENDOR_ID');

//            $table->index('coa_id'); // index for relation if needed
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};

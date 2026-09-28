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
        if (!Schema::hasColumn('shift', 'rate_change_qty')) {

            Schema::table('shift', function (Blueprint $table) {
                //
                $table->integer('rate_change_qty')->default(0);
//                $table->integer('rate_change_amount')->default(0);
            });
        }
        if (!Schema::hasColumn('shift', 'rate_change_amount')) {

            Schema::table('shift', function (Blueprint $table) {
                //
//                $table->integer('rate_change_qty')->default(0);
                $table->integer('rate_change_amount')->default(0);
            });
        }


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shifts', function (Blueprint $table) {
            //
        });
    }
};

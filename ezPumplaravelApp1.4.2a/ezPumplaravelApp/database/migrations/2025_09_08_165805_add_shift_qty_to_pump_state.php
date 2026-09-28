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
        if (!Schema::hasColumn('PUMP_STATE', 'shift_qty')) {
            Schema::table('PUMP_STATE', function (Blueprint $table) {
                $table->unsignedBigInteger('shift_qty')->nullable();
            });
        }

        if (!Schema::hasColumn('PUMP_STATE', 'shift_amt')) {
            Schema::table('PUMP_STATE', function (Blueprint $table) {
                $table->unsignedBigInteger('shift_amt')->nullable();
            });
        }
        if (!Schema::hasColumn('PUMPS', 'status_changed')) {
            Schema::table('PUMPS', function (Blueprint $table) {
                $table->integer('status_changed')->default(0);
            });
        }
        if (!Schema::hasColumn('PUMPS', 'DspID')) {
            Schema::table('PUMPS', function (Blueprint $table) {
                $table->integer('DspID')->default(1);
            });
        }
        if (!Schema::hasColumn('shiftsend', 'total_amount')) {
            Schema::table('shiftsend', function (Blueprint $table) {
                $table->unsignedBigInteger('total_amount')->default(0);
            });
        }

        if (Schema::hasColumn('shiftsend', 'total_amount')) {
            Schema::table('shiftsend', function (Blueprint $table) {
                $table->unsignedBigInteger('total_amount')->default(0)->change();
            });
        }


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pump_state', function (Blueprint $table) {
         $table->dropColumn('shift_qty');
         $table->dropColumn('shift_amt');
        });
        Schema::table('PUMPS', function (Blueprint $table) {
         $table->dropColumn('status_changed');
        });
    }
};

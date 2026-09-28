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
        // shiftsend
        if (Schema::hasTable('shiftsend')) {
            Schema::table('shiftsend', function (Blueprint $table) {
                if (Schema::hasColumn('shiftsend', 'total_qty')) {
                    $table->bigInteger('total_qty')->change();
                }
                if (Schema::hasColumn('shiftsend', 'total_amount')) {
                    $table->bigInteger('total_amount')->change();
                }
            });
        }

        // tank_stock
        if (Schema::hasTable('tank_stock')) {
            Schema::table('tank_stock', function (Blueprint $table) {
                if (Schema::hasColumn('tank_stock', 'stock_value')) {
                    $table->decimal('stock_value', 20, 2)->change();
                }
            });
        }

        // tank_stock_ledger
        if (Schema::hasTable('tank_stock_ledger')) {
            Schema::table('tank_stock_ledger', function (Blueprint $table) {
                if (Schema::hasColumn('tank_stock_ledger', 'stock_change')) {
                    $table->decimal('stock_change', 20, 2)->change();
                }
            });
        }
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shiftsent', function (Blueprint $table) {
            $table->integer('total_qty')->change();
            $table->integer('total_amount')->change();
        });

        Schema::table('tank_stock', function (Blueprint $table) {
            $table->decimal('stock_value', 10, 2)->change();
        });

        Schema::table('tank_stock_ledger', function (Blueprint $table) {
            $table->decimal('stock_change', 10, 2)->change();
        });
    }
};

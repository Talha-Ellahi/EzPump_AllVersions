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
        Schema::table('SysConfig', function (Blueprint $table) {
//            $table->string('Sys_Mode')
//                ->default(1)
//                ->comment('1 = NORMAL (NON-ATG), 2 = DISPENSER + ATG, 3 = ONLY ATG')
//                ->after('System_Configuration');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('SysConfig', function (Blueprint $table) {
            $table->dropColumn('Sys_Mode');
        });
    }
};

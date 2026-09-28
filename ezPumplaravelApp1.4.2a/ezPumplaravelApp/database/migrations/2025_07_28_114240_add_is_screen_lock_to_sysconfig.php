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
            if (!Schema::hasColumn('SysConfig', 'is_screen_lock')) {
                $table->boolean('is_screen_lock')->default(1);
                $table->boolean('email_enable')->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sysconfig', function (Blueprint $table) {
            $table->dropColumn('is_screen_lock');
            $table->dropColumn('email_enable')->default(0);

        });
    }
};

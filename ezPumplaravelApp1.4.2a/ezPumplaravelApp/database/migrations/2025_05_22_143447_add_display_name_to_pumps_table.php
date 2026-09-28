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

        if (!Schema::hasColumn('PUMPS', 'display_name')) {
            Schema::table('PUMPS', function (Blueprint $table) {
                $table->string('display_name')->nullable();
            });
            DB::statement("UPDATE PUMPS SET display_name = CONCAT(SHRT, FC_NZNo)");

        }
        // run query for setup
        // UPDATE PUMPS SET display_name = CONCAT(SHRT, POS_ID);
        // run query for update

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('PUMPS', function (Blueprint $table) {
            $table->dropColumn('display_name');
        });
    }
};

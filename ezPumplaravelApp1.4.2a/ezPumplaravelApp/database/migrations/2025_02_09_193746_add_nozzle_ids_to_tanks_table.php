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
        if (Schema::hasTable('tanks') && !Schema::hasColumn('tanks', 'nozzle_ids')) {
            Schema::table('tanks', function (Blueprint $table) {
                $table->text('nozzle_ids')->nullable()->after('product_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tanks', function (Blueprint $table) {
            $table->dropColumn('nozzle_ids');
        });
    }
};

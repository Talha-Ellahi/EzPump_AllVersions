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

        $fkName = DB::table('information_schema.key_column_usage')
            ->select('constraint_name')
            ->where('table_name', 'tank_shifts')
            ->where('column_name', 'tank_id')
            ->whereNotNull('referenced_table_name')
            ->value('constraint_name');

        // 2️⃣ Drop the FK using raw SQL
        if ($fkName) {
            DB::statement("ALTER TABLE tank_shifts DROP FOREIGN KEY {$fkName}");
        }
        // 3️⃣ Change column to INT
        Schema::table('tank_shifts', function (Blueprint $table) {
            $table->unsignedInteger('tank_id')->change();
        });

        // 4️⃣ Recreate the FK

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tank_shifts', function (Blueprint $table) {
            $table->unsignedBigInteger('tank_id')->change();
        });


    }
};

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RemoveTankMigrationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tankMigrations = [
            '2025_01_08_202600_create_tanks_table',
            '2025_01_08_202601_create_tank_stock_table',
            '2025_01_08_202602_create_dip_chart_values_table',
            '2025_02_09_154304_create_tank_shifts_table',
            '2025_02_09_155140_create_tank_shift_logs_table',
            '2025_02_09_160000_create_initial_tank_shifts',
            '2025_02_09_193746_add_nozzle_ids_to_tanks_table',
            '2025_03_26_004054_create_tank_shifts_send_table',
            '2025_01_08_202603_create_tank_stock_ledger_table',
        ];

        DB::table('migrations')
            ->whereIn('migration', $tankMigrations)
            ->delete();
        //three tables remove krna hai tanks and tank_stock tank_stock_ledger
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $tankTables = ['dip_chart_values', 'tank_stock', 'tank_stock_ledger', 'tanks'];

        foreach ($tankTables as $table) {
            if (Schema::hasTable($table)) {
                Schema::drop($table);
                $this->command->warn("⚠️ Table '{$table}' dropped.");
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('✅ Tank-related migrations removed from migrations table.');
    }
}

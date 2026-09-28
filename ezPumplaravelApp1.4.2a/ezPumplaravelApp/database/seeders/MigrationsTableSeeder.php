<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MigrationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
//        2 url  10.10.23.14 and 9 tanks migration remove krna table m
        $migrations = [
            [ 'id' => 1, 'migration' => '2014_10_12_000000_create_users_table', 'batch' => 1 ],
            [ 'id' => 2, 'migration' => '2014_10_12_100000_create_password_reset_tokens_table', 'batch' => 1 ],
            [ 'id' => 3, 'migration' => '2019_08_19_000000_create_failed_jobs_table', 'batch' => 1 ],
            [ 'id' => 4, 'migration' => '2019_12_14_000001_create_personal_access_tokens_table', 'batch' => 1 ],
            [ 'id' => 5, 'migration' => '2014_10_12_100000_create_password_resets_table', 'batch' => 2 ],
            [ 'id' => 6, 'migration' => '2024_08_03_121541_add_role_to_users_table', 'batch' => 3 ],
            [ 'id' => 7, 'migration' => '2024_08_16_195828_create_settings_table', 'batch' => 4 ],
            [ 'id' => 8, 'migration' => '2024_08_24_173447_update_payment_method_row', 'batch' => 4 ],
            [ 'id' => 9, 'migration' => '2024_09_14_161950_add_closing_feul_to_shifts', 'batch' => 5 ],
            [ 'id' => 10, 'migration' => '2024_09_20_195604_add_rate_change_qty_to_shifts', 'batch' => 6 ],
            [ 'id' => 11, 'migration' => '2024_09_27_005711_add_row_to_settings', 'batch' => 7 ],
            [ 'id' => 12, 'migration' => '2024_11_30_180307_add_unique_to_shifts', 'batch' => 8 ],
            [ 'id' => 13, 'migration' => '2024_12_18_202636_create_alert_table', 'batch' => 9 ],
            [ 'id' => 14, 'migration' => '2024_01_17_000000_allow_null_passwords', 'batch' => 10 ],
//            [ 'id' => 15, 'migration' => '2025_01_08_202600_create_tanks_table', 'batch' => 10 ],
//            [ 'id' => 16, 'migration' => '2025_01_08_202601_create_tank_stock_table', 'batch' => 10 ],
//            [ 'id' => 17, 'migration' => '2025_01_08_202602_create_dip_chart_values_table', 'batch' => 10 ],
            [ 'id' => 18, 'migration' => '2025_01_08_202603_create_tank_stock_ledger_table', 'batch' => 10 ],
            [ 'id' => 19, 'migration' => '2025_01_09_000000_add_dip_values_to_shifts', 'batch' => 10 ],
            [ 'id' => 20, 'migration' => '2025_02_09_145202_alter_shift_id_to_unsigned_big_integer_in_shift_payment_wise_sale_table', 'batch' => 11 ],
//            [ 'id' => 21, 'migration' => '2025_02_09_145203_create_shift_payment_wise_sale_table', 'batch' => 11 ],
// sid12 k liy commnetn kiye hai           [ 'id' => 22, 'migration' => '2025_02_09_154304_create_tank_shifts_table', 'batch' => 11 ],
//            [ 'id' => 23, 'migration' => '2025_02_09_155140_create_tank_shift_logs_table', 'batch' => 11 ],
//            [ 'id' => 24, 'migration' => '2025_02_09_160000_create_initial_tank_shifts', 'batch' => 11 ],
//            [ 'id' => 25, 'migration' => '2025_02_09_193746_add_nozzle_ids_to_tanks_table', 'batch' => 11 ],
            [ 'id' => 26, 'migration' => '2025_02_09_193800_add_mm_columns_to_tank_shifts_table', 'batch' => 12 ],
            [ 'id' => 27, 'migration' => '2025_03_08_123952_change_unsigned_to_signed_tank_shift', 'batch' => 13 ],
//            [ 'id' => 28, 'migration' => '2025_03_22_035200_modify_shift_payment_wise_sale_table', 'batch' => 13 ],
//            [ 'id' => 29, 'migration' => '2024_12_18_202636_create_alert_table', 'batch' => 14 ],
//            [ 'id' => 29, 'migration' => '2025_03_26_004054_create_tank_shifts_send_table', 'batch' => 13 ],
//            [ 'id' => 30, 'migration' => '2025_04_26_174400_create_eventsend_table', 'batch' => 13 ],
//            [ 'id' => 31, 'migration' => '2025_05_22_143447_add_display_name_to_pumps_table', 'batch' => 14 ],
//            [ 'id' => 32, 'migration' => '2025_05_24_130336_add_product_type_to_product_table', 'batch' => 14 ],
//            [ 'id' => 33, 'migration' => '2025_06_18_165745_create_summmary_reports_table', 'batch' => 15 ],
//            [ 'id' => 34, 'migration' => '2025_07_25_112909_add_block_status_to_user', 'batch' => 16 ],
//            [ 'id' => 35, 'migration' => '2025_07_31_114839_change_id_type_in_lastsale_table', 'batch' => 16 ],
////          [ 'id' => 36, 'migration' => '2025_07_31_115449_change_id_type_in_tank_shifts_send_table', 'batch' => 16 ],
//            [ 'id' => 37, 'migration' => '2025_07_31_142743_change_amt_type_in_salesend_table', 'batch' => 16 ],
////         [ 'id' => 38, 'migration' => '2025_07_31_143027_change_id_type_in_tank_shifts_table', 'batch' => 16 ],
//            [ 'id' => 39, 'migration' => '2025_08_01_101740_add_shift_timer_to_shiftsend_table', 'batch' => 16 ],
//            [ 'id' => 40, 'migration' => '2025_08_06_154814_create_tank_stock_event_table', 'batch' => 16 ],
//            [ 'id' => 41, 'migration' => '2025_08_15_144718_change_total_qty_to_bigint_in_shiftsent_table', 'batch' => 17 ],
//            [ 'id' => 42, 'migration' => '2025_08_15_152334_change_tank_id_to_int_in_tank_shifts', 'batch' => 18 ],
//            [ 'id' => 43, 'migration' => '2025_08_20_151312_change_totalizer_columns_to_bigint_in_tank_stock_event', 'batch' => 19 ],
//            [ 'id' => 44, 'migration' => '2025_08_21_114503_create_rate_send_table', 'batch' => 19 ],
//            [ 'id' => 45, 'migration' => '2025_09_04_163614_add_coa_id_to_two_tables', 'batch' => 20 ],
//            [ 'id' => 46, 'migration' => '2025_08_28_143403_create_chart_of_account_table', 'batch' => 21 ],
        ];

//        DB::table('migrations')->truncate(); // clean table before inserting

        foreach ($migrations as $migration) {
            \DB::table('migrations')->updateOrInsert(
                ['migration' => $migration['migration']], // unique check on migration name
                ['batch' => $migration['batch']]          // agar exist hai to batch update karega
            );
        }
    }
}

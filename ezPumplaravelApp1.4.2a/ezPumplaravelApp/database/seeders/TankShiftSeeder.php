<?php

namespace Database\Seeders;

use App\Models\Tank;
use App\Models\TankShift;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TankShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tanks = Tank::all();
        $userId = User::first()->id ?? 1;
        $count = 0;

        foreach ($tanks as $tank) {

            // 1) Create Tank Shift
            $shift = TankShift::firstOrCreate(
                [
                    'tank_id'     => $tank->id,
                    'product_id'  => $tank->product_id,
                ],
                [
                    'opening_mm' => 0,
                    'closing_mm' => 0,
                    'user_id'    => $userId,
                    'start_time' => now(),
                ]
            );

            if ($shift->wasRecentlyCreated) {
                $count++;
            }

            // 2) Create Tank Stock If Missing
            $existingStock = DB::table('tank_stock')->where('tank_id', $tank->id)->first();

            if (!$existingStock) {
                DB::table('tank_stock')->insert([
                    'tank_id'     => $tank->id,
                    'stock_value' => 0,       // default value
                    'millimeter'  => 0,       // default mm
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
            }
        }


        $this->command->info("✅ {$count} new tank shifts created.");
    }
}

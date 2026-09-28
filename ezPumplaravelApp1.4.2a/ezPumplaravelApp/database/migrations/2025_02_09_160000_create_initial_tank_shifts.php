<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Tank;
use App\Models\TankShift;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
//        $tanks = Tank::all();
//        $userId = User::first()->id; // Assuming there is at least one user
//
//        foreach ($tanks as $tank) {
//            TankShift::create([
//                'tank_id' => $tank->id,
//                'product_id' => $tank->product_id, // Assuming fuel_type holds the product_id
//                'opening_totalizer' => 0,
//                'closing_totalizer' => 0,
//                'manual_opening_totalizer' => 0,
//                'manual_closing_totalizer' => 0,
//                'user_id' => $userId,
//                'is_modified' => false,
//            ]);
//        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        TankShift::truncate();
    }
};

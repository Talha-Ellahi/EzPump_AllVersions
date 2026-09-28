<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tank extends Model
{
    use HasFactory;

    protected $fillable = [
        'tank_name',
        'fuel_type',
        'capacity_liters',
        'temperature',
        'water_height_mm',
        'product_id',
        'nozzle_ids',
        'is_active',
        'low_level_alarm_mm',
        'low_low_level_alarm_mm',
        'high_level_alarm_mm',
        'high_high_level_alarm_mm',
        'is_swap',
        'swapped_with_tank_id',
        'is_swapped',
    ];

    public function dipChartValues()
    {
        return $this->hasMany(DipChartValue::class);
    }

    public function stockLedger()
    {
        return $this->hasMany(TankStockLedger::class);
    }

    public function stock()
    {
        return $this->hasOne(TankStock::class);
    }
//    protected static function booted()
//    {
//        // 🔴 GLOBAL FILTER — active tanks hide everywhere
//        static::addGlobalScope('hideActiveTanks', function (Builder $builder) {
//            $builder->where('is_active', '!=', 0);
//        });
//    }
}

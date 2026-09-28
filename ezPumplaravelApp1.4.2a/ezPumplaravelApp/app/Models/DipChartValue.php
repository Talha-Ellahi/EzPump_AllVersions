<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DipChartValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'tank_id',
        'millimeter',
        'liter_value'
    ];

    public function tank()
    {
        return $this->belongsTo(Tank::class);
    }
}

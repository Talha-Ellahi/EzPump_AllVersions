<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TankStock extends Model
{
    use HasFactory;
    protected $table = 'tank_stock';
    protected $fillable = [
        'tank_id',
        'stock_value',
        'millimeter'
    ];

    public function tank()
    {
        return $this->belongsTo(Tank::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TankShiftLog extends Model
{
    use HasFactory;


    protected $fillable = [
        'tank_id',
        'product_id',
        'opening_dip',
        'closing_dip',
        'manual_opening_dip',
        'manual_closing_dip',

        'user_id',
        'is_modified',
        'data',
        'start_time',
        'end_time',
    ];
    protected $casts = [
        'data' => 'json',
    ];

    public function tank()
    {
        return $this->belongsTo(Tank::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'ICODE');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

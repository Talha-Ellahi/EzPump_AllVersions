<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TankShift extends Model
{
    use HasFactory;

//    protected $fillable = [
//        'tank_id',
//        'product_id',
//        'opening_totalizer',
//        'closing_totalizer',
//        'manual_opening_totalizer',
//        'manual_closing_totalizer',
//        'opening_mm',
//        'closing_mm',
//        'manual_opening_mm',
//        'manual_closing_mm',
//        'user_id',
//        'is_modified',
//        'start_time',
//        'end_time',
//    ];
    protected $fillable = [
        'tank_id',
        'product_id',
        'opening_mm',
        'closing_mm',
//        'manual_opening_totalizer',
//        'manual_closing_totalizer',
//        'opening_mm',
//        'closing_mm',
//        'manual_opening_mm',
//        'manual_closing_mm',
        'user_id',
//        'is_modified',
        'start_time',
        'end_time',
        'calendar_id'
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

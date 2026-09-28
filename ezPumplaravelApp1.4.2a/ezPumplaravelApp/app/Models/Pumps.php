<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pumps extends Model
{
    use HasFactory;

    protected $table = 'PUMPS';
    protected $primaryKey = 'id';

    protected $fillable = [
        'POS_ID',
        'FC_NZNo',
        'ICODE',
        'SHRT',
        'TNO',
        'AmtD',
        'QtyD',
        'RateD',
        'TOTLD',
        'STOP_TIME',
        'Sts',
        'PrintEnable',
        'PrntID',
        'PrintMinAmount',
        'CLOSED'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'ICODE', 'ICODE');
    }
    public function shift()
    {
//        return $this->hasOne(Shift::class, 'pump_id', 'POS_ID');
        return $this->hasOne(Shift::class, 'pump_id', 'FC_NZNo');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShiftPaymentWiseSale extends Model
{
    use HasFactory;

    protected $table = 'shift_payment_wise_sale';

    public $timestamps = false;

    protected $fillable = [
        'shift_id',
        'paymentmethod_id',
        'total_sale',
        'total_qty',
        'price'
    ];

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }
}

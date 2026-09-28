<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;
    protected $table='shift';
    public $timestamps = false; // Exclude created_at and updated_at columns

    protected $fillable = [
        'opening_fuel',
        'closing_fuel',
        'opening_fuel_manual',
        'closing_fuel_manual',
        'opening_balance',
        'closing_balance',
        'adjustments',
        'total_qty',
        'status',
        'rate',
        'is_changed',
        'new_rate',
        'changed_fuel_balance',
        'pump_id',
        'cashier_name',
        'last_sale_id',
        'start_date',
        'end_date',
        'bIsUpdated',
        'cashier_id',
        'icode',
        'opening_dip',            // Newly added columns
        'closing_dip',            // Newly added columns
        'opening_dip_manual',     // Newly added columns
        'closing_dip_manual',     // Newly added columns
        'manual_opening_dip' ,    // Newly added columns
            'manual_closing_dip',    // Newly added columns
            'tank_id' ,    // Newly added columns
            'shiftTimer' ,    // Newly added columns
            'shift_type' ,    // Newly added columns
        'calendar_id',
    ];

    public function shiftPaymentWiseSales()
    {
        return $this->hasMany(ShiftPaymentWiseSale::class);
    }

//    public function getOrCreatePaymentWiseSale($paymentmethodId)
//    {
//
//        return $this->shiftPaymentWiseSales()->firstOrCreate(
//            ['paymentmethod_id' => $paymentmethodId, 'price' => (isset($this->new_rate) && $this->new_rate != 0) ? $this->new_rate : $this->rate],
//            [
//                'total_sale' => 0,
//                'total_qty' => 0,
//
//            ]
//        );
//    }

    public function addSale($paymentmethodId, $qty, $totalSale)
    {
//        $paymentWiseSale = $this->getOrCreatePaymentWiseSale($paymentmethodId);
//
//        $paymentWiseSale->total_qty += $qty;
//        $paymentWiseSale->total_sale += $totalSale;
//        $paymentWiseSale->save();

//        return $paymentWiseSale;
    }
}

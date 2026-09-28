<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class saledata extends Model
{
    use HasFactory;

    protected $table = 'saledata';
    protected $primaryKey = 'id';
    public $timestamps = false; // Assuming you don't want created_at and updated_at fields
    protected $fillable = [
        'pdate',
        'tdate',
        'pos_id',
//        'ForeCourt_NzNo',
    'FC_NZNo',
        'icode',
        'qty',
        'rate',
        'amt',
        'totalizer',
        'FSN',
        'VTotalizer',
        'p_mode',
        'customer_id',
        'vid',
        'RegNO',
        'bPrint',
        'user_id',
        'shift_id',
        'erp_id',
        'bIsUpdated'
    ];

    protected $casts = [
        'pdate' => 'datetime',
        'tdate' => 'datetime',
        'bPrint' => 'boolean',
        'bIsUpdated' => 'boolean'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'icode', 'ICODE');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'p_mode');
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class, 'shift_id');
    }
}

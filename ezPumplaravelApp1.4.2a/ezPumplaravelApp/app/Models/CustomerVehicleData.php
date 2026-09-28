<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerVehicleData extends Model
{
    use HasFactory;
    
    protected $table = 'customer_vehicle_data';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'customer_id',
        'vid',
        'RegNO',
        'icode',
        'SRATE',
        'CreditLimit',
        'LimitUsed',
        'VehBlocked'
    ];
    
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    
    public function product()
    {
        return $this->belongsTo(Product::class, 'icode', 'ICODE');
    }
}

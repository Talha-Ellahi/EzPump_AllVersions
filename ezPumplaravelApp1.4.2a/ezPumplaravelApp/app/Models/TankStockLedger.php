<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TankStockLedger extends Model
{
    use HasFactory;
    protected $table = 'tank_stock_ledger';
    protected $fillable = [
        'tank_id',
        'transaction_type',
        'stock_change',
        'millimeter',
        'comments',
        'stock_change',
        'icode',
        'vendor_id',
        'adjustment',
        'adjustment_stock',
    ];

    public function tank()
    {
        return $this->belongsTo(Tank::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'PRODUCT';
    protected $primaryKey = 'ICODE';

    protected $fillable = [
        'ITMNAME',
        'UOM',
        'PRATE',
        'SRATE',
        'DB_CODE',
        'SHRT',
        'CLOSED',
        'RT',
        'ICODE'
    ];

}

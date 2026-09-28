<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class paymentmethod extends Model
{
    use HasFactory;
    
    protected $table = 'paymentmethod';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'erp_id',
        'Des',
        'logo_profile'
    ];
}

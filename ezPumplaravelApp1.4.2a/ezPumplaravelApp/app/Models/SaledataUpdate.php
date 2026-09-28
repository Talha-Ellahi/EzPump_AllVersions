<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaledataUpdate extends Model
{
    use HasFactory;
    protected $table ="saledata_update";
    protected $primaryKey ="id";
    public $timestamps = false; // Exclude created_at and updated_at columns

}

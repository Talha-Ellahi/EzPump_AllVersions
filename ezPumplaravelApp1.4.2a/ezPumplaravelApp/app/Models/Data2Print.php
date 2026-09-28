<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Data2Print extends Model
{
    use HasFactory;
    protected $table ="data2print";
    protected $primaryKey ="id";
    public $timestamps = false; // Exclude created_at and updated_at columns

}

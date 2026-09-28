<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'alert';
    protected $fillable = [
        'key',
        'value',
'description',
'type',
    ];
}

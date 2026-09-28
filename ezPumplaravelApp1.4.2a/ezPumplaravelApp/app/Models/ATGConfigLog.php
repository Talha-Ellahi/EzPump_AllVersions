<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ATGConfigLog extends Model
{
    use HasFactory;

    protected $table = 'atg_config_logs'; // specify the table name

    protected $fillable = [
        'tank_id',
        'command',   // Read or Write
        'csv_data',  // JSON data
        'status',    // success or error
        'is_active',
    ];

    protected $casts = [
        'csv_data' => 'array', // automatically cast JSON to array
        'is_active' => 'boolean',
    ];
}

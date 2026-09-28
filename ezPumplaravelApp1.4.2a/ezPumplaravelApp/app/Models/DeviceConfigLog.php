<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceConfigLog extends Model
{
    use HasFactory;
    protected $table = 'device_config_logs';

    protected $fillable = [
        'dispenser_id',
        'command',
        'csv_data',
        'status',
        'is_active',
    ];

    protected $casts = [
        'csv_data' => 'array',
    ];
}

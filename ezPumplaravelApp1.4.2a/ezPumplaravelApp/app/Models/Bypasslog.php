<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bypasslog extends Model
{
    use HasFactory;
    protected $table = 'bypass_log';
    protected $fillable = ['bypass_limit_id', 'log_data'];

    protected $casts = [
        'log_data' => 'array'
    ];

    public function limit()
    {
        return $this->belongsTo(BypassLimit::class, 'bypass_limit_id');
    }
}

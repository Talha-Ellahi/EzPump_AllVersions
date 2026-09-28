<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BypassRecord extends Model
{
    use HasFactory;
    protected $table = 'bypass_record';
    protected $fillable = ['bypass_limit_id', 'used_limit', 'remaining_limit','add_hours'];

    public function limit()
    {
        return $this->belongsTo(BypassLimit::class, 'bypass_limit_id');
    }
}

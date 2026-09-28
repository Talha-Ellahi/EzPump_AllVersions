<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BypassLimit extends Model
{
    use HasFactory;
    protected $table = 'bypass_limit';
    protected $fillable = ['sys_id', 'month', 'total_limit'];

    public function records()
    {
        return $this->hasMany(BypassRecord::class, 'bypass_limit_id');
    }

    public function logs()
    {
        return $this->hasMany(BypassLog::class, 'bypass_limit_id');
    }
}

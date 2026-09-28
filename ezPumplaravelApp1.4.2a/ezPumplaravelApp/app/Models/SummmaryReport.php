<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SummmaryReport extends Model
{
    protected $table = 'summmary_reports';
    protected $fillable = ['FormData','calendar_id'];
    use HasFactory;
}

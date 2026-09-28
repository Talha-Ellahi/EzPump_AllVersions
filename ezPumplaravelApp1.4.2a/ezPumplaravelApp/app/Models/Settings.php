<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = "settings";

     /**
     * Get settings as an associative array [key => value].
     *
     * @return array
     */
    public static function getSettingsArray(): array
    {
        return self::pluck('value', 'key')->toArray();
    }
}

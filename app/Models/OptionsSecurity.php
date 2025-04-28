<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OptionsSecurity extends Model
{
    use HasFactory;

    protected $table = 'options_security';
    protected $fillable = [
        'maintenance',
        'maintenance_message',
        'whitelist',
    ];
}

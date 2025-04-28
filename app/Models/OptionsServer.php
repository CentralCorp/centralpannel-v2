<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OptionsServer extends Model
{
    use HasFactory;

    protected $table = 'options_server';
    protected $fillable = [
        'server_name',
        'server_ip',
        'server_port',
        'icon'
    ];
}

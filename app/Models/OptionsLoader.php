<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OptionsLoader extends Model
{
    protected $table = 'options_loader';

    protected $fillable = [
        'minecraft_version',
        'loader_activation',
        'loader_type',
        'loader_forge_version',
        'loader_build_version',
    ];
}


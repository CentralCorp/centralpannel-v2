<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OptionsMods extends Model
{
    protected $table = 'mods'; // Nom de la table dans la base de données
    protected $fillable = ['file', 'name', 'description', 'icon', 'optional', 'recommended'];
}

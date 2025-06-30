<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OptionsGeneral extends Model
{
    use HasFactory;

    protected $table = 'options_general';

    protected $fillable = [
        'mods_enabled',
        'file_verification',
        'embedded_java',
        'game_folder_name',
        'email_verified',
        'role_display',
        'money_display',
        'azuriom_url',
        'azuriom_api_key',
        'market_api_key',
        'active_theme_id',
        'active_theme_version',
        'min_ram',
        'max_ram',
    ];
}


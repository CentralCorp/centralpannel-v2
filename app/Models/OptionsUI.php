<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OptionsUI extends Model
{
    use HasFactory;

    protected $table = 'options_ui';
    protected $fillable = [
        'alert_activation',
        'alert_scroll',
        'alert_msg',
        'video_activation',
        'video_url',
        'splash',
        'splash_author',
    ];
}

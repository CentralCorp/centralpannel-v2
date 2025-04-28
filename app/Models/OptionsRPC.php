<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OptionsRPC extends Model
{
    use HasFactory;

    protected $table = 'options_rpc'; // Assurez-vous que la table correspond à votre migration
    protected $fillable = [
        'rpc_activation',
        'rpc_id',
        'rpc_details',
        'rpc_state',
        'rpc_large_text',
        'rpc_small_text',
        'rpc_button1',
        'rpc_button1_url',
        'rpc_button2',
        'rpc_button2_url'
    ];
}

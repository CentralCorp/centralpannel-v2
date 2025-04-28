<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OptionsWhitelistRole extends Model
{
    use HasFactory;

    protected $table = 'whitelist_roles';
    protected $fillable = ['role'];
}


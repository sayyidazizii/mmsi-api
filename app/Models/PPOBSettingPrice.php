<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PPOBSettingPrice extends Model
{
    // use HasFactory;
    protected $connection = 'mysql';
    protected $table = 'ppob_setting_price'; 
    protected $primaryKey = 'setting_price_id';
    protected $guarded = ['setting_price_id', 'created_on', 'last_update'];
    const CREATED_AT = 'created_on';
    const UPDATED_AT = 'last_update';
}

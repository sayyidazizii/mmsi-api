<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    // use HasFactory;
    protected $table        = 'system_setting'; 
    protected $primaryKey   = 'setting_id';
    protected $guarded      = ['setting_id', 'system_version', 'last_update'];
    const CREATED_AT        = 'created_on';
    const UPDATED_AT        = 'last_update';
}

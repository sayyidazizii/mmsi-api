<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogLogin extends Model
{
    // use HasFactory;
    protected $connection   = 'mysql';
    protected $table        = 'system_log_login'; 
    protected $primaryKey   = 'log_login_id';
    protected $fillable     = ['member_id', 'member_no', 'imei', 'created_on'];
    protected $hidden = [
        'imei',
    ];
    const CREATED_AT = 'created_on';
    const UPDATED_AT = 'last_update';
}

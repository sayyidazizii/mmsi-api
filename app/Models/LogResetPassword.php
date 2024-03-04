<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogResetPassword extends Model
{
    // use HasFactory;
    protected $connection   = 'mysql';
    protected $table        = 'system_log_reset_password'; 
    protected $primaryKey   = 'log_reset_password_id';
    protected $fillable     = ['member_id', 'member_no', 'user_id', 'created_on'];
    protected $hidden       = [
    ];
    const CREATED_AT = 'created_on';
    const UPDATED_AT = 'last_update';
}

<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogChangePassword extends Model
{
    // use HasFactory;
    protected $connection   = 'mysql';
    protected $table        = 'system_log_change_password'; 
    protected $primaryKey   = 'log_change_password_id';
    protected $fillable     = ['member_id', 'member_no', 'user_id', 'created_on'];
    protected $hidden       = [
    ];
    const CREATED_AT = 'created_on';
    const UPDATED_AT = 'last_update';
}

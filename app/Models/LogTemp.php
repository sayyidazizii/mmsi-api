<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogTemp extends Model
{
    // use HasFactory;
    protected $connection   = 'mysql';
    protected $table        = 'system_log_temp'; 
    protected $primaryKey   = 'log_temp_id';
    protected $fillable     = ['member_id', 'member_no', 'log', 'logt', 'created_on'];
    protected $hidden       = [
    ];
    const CREATED_AT = 'created_on';
    const UPDATED_AT = 'last_update';
}

<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogOpenBlock extends Model
{
    // use HasFactory;
    protected $connection   = 'mysql';
    protected $table        = 'system_log_open_block'; 
    protected $primaryKey   = 'log_open_block_id';
    protected $fillable     = ['member_id', 'member_no', 'member_imei', 'created_on'];
    protected $hidden = [
        'member_imei',
    ];
    const CREATED_AT = 'created_on';
    const UPDATED_AT = 'last_update';
}

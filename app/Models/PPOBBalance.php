<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PPOBBalance extends Model
{
    // use HasFactory;
    protected $connection = 'mysql';
    protected $table = 'ppob_balance'; 
    protected $primaryKey = 'ppob_balance_id';
    protected $guarded = ['ppob_balance_id', 'created_on', 'last_update'];
    const CREATED_AT = 'created_on';
    const UPDATED_AT = 'last_update';
}

<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PPOBTopUp extends Model
{
    // use HasFactory;
    protected $connection = 'mysql';
    protected $table = 'ppob_topup'; 
    protected $primaryKey = 'ppob_topup_id';
    protected $guarded = ['ppob_topup_id', 'created_on', 'last_update'];
    const CREATED_AT = 'created_on';
    const UPDATED_AT = 'last_update';
}

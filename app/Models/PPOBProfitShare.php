<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PPOBProfitShare extends Model
{
    // use HasFactory;
    protected $connection = 'mysql';
    protected $table = 'ppob_profit_share'; 
    protected $primaryKey = 'ppob_profit_share_id';
    protected $guarded = ['ppob_profit_share_id', 'created_on', 'last_update'];
    const CREATED_AT = 'created_on';
    const UPDATED_AT = 'last_update';
}

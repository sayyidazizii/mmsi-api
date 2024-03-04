<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcctSavings extends Model
{
    // use HasFactory;
    protected $connection = 'mysql3';
    protected $table = 'acct_savings'; 
    protected $primaryKey = 'savings_id';
    protected $guarded = ['savings_id', 'created_on', 'last_update'];
    const CREATED_AT = 'created_on';
    const UPDATED_AT = 'last_update';
}

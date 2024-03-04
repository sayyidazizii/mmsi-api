<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcctAccount extends Model
{
    // use HasFactory;
    protected $connection = 'mysql3';
    protected $table = 'acct_account'; 
    protected $primaryKey = 'account_id';
    protected $guarded = ['account_id', 'created_on', 'last_update'];
    const CREATED_AT = 'created_on';
    const UPDATED_AT = 'last_update';
}

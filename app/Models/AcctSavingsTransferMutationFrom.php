<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcctSavingsTransferMutationFrom extends Model
{
    // use HasFactory;
    protected $connection = 'mysql3';
    protected $table = 'acct_savings_transfer_mutation_from'; 
    protected $primaryKey = 'savings_transfer_mutation_from_id';
    protected $guarded = ['savings_transfer_mutation_from_id', 'created_on', 'last_update'];
    const CREATED_AT = 'created_on';
    const UPDATED_AT = 'last_update';
}

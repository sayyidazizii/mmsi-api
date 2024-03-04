<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcctSavingsTransferMutationTo extends Model
{
    // use HasFactory;
    protected $connection = 'mysql3';
    protected $table = 'acct_savings_transfer_mutation_to'; 
    protected $primaryKey = 'savings_transfer_mutation_to_id';
    protected $guarded = ['savings_transfer_mutation_to_id', 'created_on', 'last_update'];
    const CREATED_AT = 'created_on';
    const UPDATED_AT = 'last_update';
}

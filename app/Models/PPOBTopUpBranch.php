<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PPOBTopUpBranch extends Model
{
    // use HasFactory;
    protected $connection = 'mysql';
    protected $table = 'ppob_topup_branch'; 
    protected $primaryKey = 'topup_branch_id';
    protected $guarded = ['topup_branch_id', 'created_on', 'last_update'];
    const CREATED_AT = 'created_on';
    const UPDATED_AT = 'last_update';
}

<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PPOBCompanyCipta extends Model
{
    // use HasFactory;
    protected $connection   = 'mysql2';
    protected $table        = 'ppob_company'; 
    protected $primaryKey   = 'ppob_company_id';
    protected $guarded      = ['ppob_company_id', 'created_on', 'last_update'];
    const CREATED_AT        = 'created_on';
    const UPDATED_AT        = 'last_update';
}

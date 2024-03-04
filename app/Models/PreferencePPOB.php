<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreferencePPOB extends Model
{
    // use HasFactory;
    protected $connection   = 'mysql';
    protected $table        = 'preference_ppob'; 
    protected $primaryKey   = 'id';
    protected $guarded      = ['id', 'created_on', 'last_update'];
    const CREATED_AT        = 'created_on';
    const UPDATED_AT        = 'last_update';
}

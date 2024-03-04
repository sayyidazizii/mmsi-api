<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoreProgram extends Model
{
    // use HasFactory;
    protected $connection   = 'mysql3';
    protected $table        = 'core_program'; 
    protected $primaryKey   = 'program_id';
    protected $guarded      = ['program_id', 'program_name', 'program_photo', 'program_remark', 'last_update'];
    const CREATED_AT        = 'created_on';
    const UPDATED_AT        = 'last_update';
}

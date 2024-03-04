<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoreMember extends Model
{
    // use HasFactory;
    protected $connection = 'mysql3';
    protected $table = 'core_member'; 
    protected $primaryKey = 'member_id';
    protected $guarded = ['member_id', 'created_on', 'last_update'];
    const CREATED_AT = 'created_on';
    const UPDATED_AT = 'last_update';
}

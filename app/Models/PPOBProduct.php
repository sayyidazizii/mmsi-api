<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PPOBProduct extends Model
{
    // use HasFactory;
    protected $connection = 'mysql';
    protected $table = 'ppob_product'; 
    protected $primaryKey = 'product_id';
    protected $guarded = ['product_id', 'created_on', 'last_update'];
    const CREATED_AT = 'created_on';
    const UPDATED_AT = 'last_update';
}

<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // use HasFactory;
    protected $connection = 'mysql2';
    protected $table = 'ppob_product'; 
    protected $primaryKey = 'ppob_product_id';
    protected $guarded = ['ppob_product_id', 'created_on', 'last_update'];
    const CREATED_AT = 'created_on';
    const UPDATED_AT = 'last_update';
}

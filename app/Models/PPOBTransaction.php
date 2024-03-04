<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PPOBTransaction extends Model
{
    // use HasFactory;
    protected $connection = 'mysql';
    protected $table = 'ppob_transaction'; 
    protected $primaryKey = 'ppob_transaction_id';
    // protected $fillable = ['ppob_transaction_no', 'ppob_unique_code', 'ppob_company_id', 'ppob_agen_id', 'ppob_agen_name', 'ppob_product_category_id', 'ppob_product_id', 'savings_account_id', 'savings_id', 'member_id', 'branch_id', 'transaction_id'];
    protected $guarded = ['ppob_transaction_id', 'created_on', 'last_update'];
    const CREATED_AT = 'created_on';
    const UPDATED_AT = 'last_update';
}

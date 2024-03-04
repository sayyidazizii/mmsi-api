<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcctJournalVoucherItem extends Model
{
    // use HasFactory;
    protected $connection = 'mysql3';
    protected $table = 'acct_journal_voucher_item'; 
    protected $primaryKey = 'journal_voucher_item_id';
    protected $guarded = ['journal_voucher_item_id', 'created_on', 'last_update'];
    const CREATED_AT = 'created_on';
    const UPDATED_AT = 'last_update';
}

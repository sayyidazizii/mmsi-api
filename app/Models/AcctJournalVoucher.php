<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcctJournalVoucher extends Model
{
    // use HasFactory;
    protected $connection = 'mysql3';
    protected $table = 'acct_journal_voucher'; 
    protected $primaryKey = 'journal_voucher_id';
    protected $guarded = ['journal_voucher_id', 'created_on', 'last_update'];
    const CREATED_AT = 'created_on';
    const UPDATED_AT = 'last_update';
}

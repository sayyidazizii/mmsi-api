<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappOTP extends Model
{
    // use HasFactory;
    protected $connection   = 'mysql';
    protected $table        = 'whatsapp_otp'; 
    protected $primaryKey   = 'otp_id';
    protected $fillable     = ['otp_id', 'member_id', 'otp_code', 'created_on'];
    protected $hidden       = [
    ];
    const CREATED_AT = 'created_on';
    const UPDATED_AT = 'last_update';
}

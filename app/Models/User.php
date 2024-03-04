<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    protected $connection   = 'mysql';
    protected $table        = 'system_user'; 
    protected $primaryKey   = 'user_id';
    const CREATED_AT        = 'created_on';
    const UPDATED_AT        = 'last_update';

    protected $fillable = [
        'member_id',
        'member_no',
        'password',
        'password_transaksi',
        'member_name',
        'member_phone',
        'branch_id',
        'log_state',
        'block_state',
        'otp_state',
        'member_user_status',
        'expired_on'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'password_transaksi',
        'remember_token',
        'created_on',
        'last_update',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}

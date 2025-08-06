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

    protected $primaryKey = 'user_id';
    public $incrementing = false;
    protected $keyType = 'uuid';

    protected $fillable = [
        'full_name',
        'email',
        'phone_number',
        'password_hash',
        'role',
        'account_status',
        'profile_picture_url',
        'two_factor_enabled'
    ];

    protected $hidden = [
        'password_hash',
        'password_reset_token',
        'remember_token',
    ];

    protected $casts = [
        'last_login' => 'datetime',
        'password_reset_expires' => 'datetime',
        'two_factor_enabled' => 'boolean',
    ];

    public function addresses()
    {
        return $this->hasMany(Address::class, 'user_id');
    }

    public function provider()
    {
        return $this->hasOne(Provider::class, 'user_id');
    }

    public function userPolicies()
    {
        return $this->hasMany(user_policies::class, 'user_id');
    }

    public function kycVerifications()
    {
        return $this->hasMany(KycVerification::class, 'user_id');
    }

    public function notifications()
    {
        return $this->hasMany(notifications::class, 'user_id');
    }

    public function transactions()
    {
        return $this->hasMany(transactions::class, 'user_id');
    }

    public function processedClaims()
    {
        return $this->hasMany(claims::class, 'processed_by');
    }

    public function refundsProcessed()
    {
        return $this->hasMany(refunds::class, 'processed_by');
    }
};
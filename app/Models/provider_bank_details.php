<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class provider_bank_details extends Model
{

    use HasFactory;

    protected $primaryKey = 'bank_id';
    
    protected $fillable = [
        'provider_id',
        'account_name',
        'account_number',
        'bank_name',
        'branch_code',
        'swift_code',
        'iban',
        'currency',
        'is_verified',
        'verified_at'
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
    ];

    public function provider()
    {
        return $this->belongsTo(Provider::class, 'provider_id');
    }
}
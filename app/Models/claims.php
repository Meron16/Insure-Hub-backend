<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class claims extends Model
{
    use HasFactory;

    protected $primaryKey = 'claim_id';
    
    protected $fillable = [
        'user_policy_id',
        'provider_id',
        'claim_number',
        'claim_amount',
        'approved_amount',
        'description',
        'incident_date',
        'incident_location',
        'status',
        'processed_by',
        'processed_at',
        'rejection_reason',
        'payment_date',
        'claim_type'
    ];

    protected $casts = [
        'claim_amount' => 'decimal:2',
        'approved_amount' => 'decimal:2',
        'incident_date' => 'date',
        'processed_at' => 'datetime',
        'payment_date' => 'date',
    ];

    public function userPolicy()
    {
        return $this->belongsTo(user_policies::class, 'user_policy_id');
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class, 'provider_id');
    }

    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}
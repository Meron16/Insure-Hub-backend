<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class user_policies extends Model
{
    use HasFactory;

    protected $primaryKey = 'user_policy_id';
    
    protected $fillable = [
        'user_id',
        'policy_id',
        'policy_number',
        'start_date',
        'end_date',
        'status',
        'payment_frequency',
        'next_payment_date',
        'last_payment_date',
        'total_premium',
        'paid_amount',
        'payment_method',
        'auto_renew',
        'cancellation_reason',
        'signed_document_url'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'next_payment_date' => 'date',
        'last_payment_date' => 'date',
        'total_premium' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'auto_renew' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function policy()
    {
        return $this->belongsTo(policies::class, 'policy_id');
    }

    public function claims()
    {
        return $this->hasMany(claims::class, 'user_policy_id');
    }

    public function transactions()
    {
        return $this->hasMany(transactions::class, 'user_policy_id');
    }
}

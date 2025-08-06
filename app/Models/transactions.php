<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class transactions extends Model
{
    use HasFactory;

    protected $primaryKey = 'transaction_id';
    
    protected $fillable = [
        'user_id',
        'policy_id',
        'user_policy_id',
        'amount',
        'payment_method',
        'payment_method_details',
        'transaction_reference',
        'status',
        'failure_reason',
        'invoice_url'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_method_details' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function policy()
    {
        return $this->belongsTo(policies::class, 'policy_id');
    }

    public function userPolicy()
    {
        return $this->belongsTo(user_policies::class, 'user_policy_id');
    }

    public function refund()
    {
        return $this->hasOne(refunds::class, 'transaction_id');
    }
}
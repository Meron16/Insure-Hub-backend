<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class policies extends Model
{
    use HasFactory;

    protected $primaryKey = 'policy_id';
    
    protected $fillable = [
        'provider_id',
        'category_id',
        'title',
        'description',
        'premium_amount',
        'coverage_limit',
        'deductible_amount',
        'duration_days',
        'renewal_type',
        'grace_period_days',
        'is_active',
        'requires_medical_check',
        'min_age',
        'max_age',
        'approved_by_admin',
        'approval_date',
        'terms_and_conditions_url'
    ];

    protected $casts = [
        'premium_amount' => 'decimal:2',
        'coverage_limit' => 'decimal:2',
        'deductible_amount' => 'decimal:2',
        'is_active' => 'boolean',
        'requires_medical_check' => 'boolean',
        'approved_by_admin' => 'boolean',
        'approval_date' => 'datetime',
    ];

    public function provider()
    {
        return $this->belongsTo(Provider::class, 'provider_id');
    }

    public function category()
    {
        return $this->belongsTo(insurance_categories::class, 'category_id');
    }

    public function userPolicies()
    {
        return $this->hasMany(user_policies::class, 'policy_id');
    }

    public function transactions()
    {
        return $this->hasManyThrough(transactions::class, user_policies::class);
    }
}
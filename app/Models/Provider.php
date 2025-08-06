<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    use HasFactory;

    protected $primaryKey = 'provider_id';
    
    protected $fillable = [
        'user_id',
        'company_name',
        'license_number',
        'website_url',
        'contact_person',
        'contact_email',
        'contact_phone',
        'is_approved',
        'approval_date',
        'rating',
        'years_in_business',
        'description'
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'approval_date' => 'datetime',
        'rating' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function bankDetails()
    {
        return $this->hasOne(provider_bank_details::class, 'provider_id');
    }

    public function documents()
    {
        return $this->hasMany(provider_documents::class, 'provider_id');
    }

    public function policies()
    {
        return $this->hasMany(policies::class, 'provider_id');
    }

    public function claims()
    {
        return $this->hasMany(claims::class, 'provider_id');
    }
}
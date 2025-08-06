<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KycVerification extends Model
{
    use HasFactory;

    protected $primaryKey = 'kyc_id';
    
    protected $fillable = [
        'user_id',
        'document_type',
        'document_number',
        'front_image_url',
        'back_image_url',
        'selfie_with_doc_url',
        'status',
        'rejection_reason',
        'verified_by',
        'verified_at',
        'expiry_date'
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'expiry_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class KycVerificationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'kyc_id' => $this->kyc_id,
            'user_id' => $this->user_id,
            'document_type' => $this->document_type,
            'document_number' => $this->document_number,
            'front_image_url' => $this->front_image_url,
            'back_image_url' => $this->back_image_url,
            'selfie_with_doc_url' => $this->selfie_with_doc_url,
            'status' => $this->status,
            'rejection_reason' => $this->rejection_reason,
            'verified_by' => $this->verified_by,
            'verified_at' => $this->verified_at,
            'expiry_date' => $this->expiry_date,
            
            'user' => [
                'full_name' => $this->user->full_name,
                'email' => $this->user->email,
            ],
            
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
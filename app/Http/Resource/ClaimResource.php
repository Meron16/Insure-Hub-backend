<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClaimResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'claim_id' => $this->claim_id,
            'user_policy_id' => $this->user_policy_id,
            'provider_id' => $this->provider_id,
            'claim_number' => $this->claim_number,
            'claim_amount' => $this->claim_amount,
            'approved_amount' => $this->approved_amount,
            'description' => $this->description,
            'incident_date' => $this->incident_date,
            'incident_location' => $this->incident_location,
            'status' => $this->status,
            'processed_by' => $this->processed_by,
            'processed_at' => $this->processed_at,
            'rejection_reason' => $this->rejection_reason,
            'payment_date' => $this->payment_date,
            'claim_type' => $this->claim_type,
            
            'policy' => [
                'policy_number' => $this->userPolicy->policy_number,
                'coverage_limit' => $this->userPolicy->policy->coverage_limit,
            ],
            
            'provider' => [
                'company_name' => $this->provider->company_name,
                'contact_phone' => $this->provider->contact_phone,
            ],
            
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
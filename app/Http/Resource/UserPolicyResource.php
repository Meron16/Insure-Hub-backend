<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserPolicyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'user_policy_id' => $this->user_policy_id,
            'user_id' => $this->user_id,
            'policy_id' => $this->policy_id,
            'policy_number' => $this->policy_number,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => $this->status,
            'payment_frequency' => $this->payment_frequency,
            'next_payment_date' => $this->next_payment_date,
            'last_payment_date' => $this->last_payment_date,
            'total_premium' => $this->total_premium,
            'paid_amount' => $this->paid_amount,
            'payment_method' => $this->payment_method,
            'auto_renew' => $this->auto_renew,
            'signed_document_url' => $this->signed_document_url,
            
            'policy' => [
                'title' => $this->policy->title,
                'coverage_limit' => $this->policy->coverage_limit,
                'provider_name' => $this->policy->provider->company_name,
            ],
            
            'user' => [
                'full_name' => $this->user->full_name,
                'email' => $this->user->email,
            ],
            
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
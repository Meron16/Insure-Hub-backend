<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PolicyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'policy_id' => $this->policy_id,
            'provider_id' => $this->provider_id,
            'category_id' => $this->category_id,
            'title' => $this->title,
            'description' => $this->description,
            'premium_amount' => $this->premium_amount,
            'coverage_limit' => $this->coverage_limit,
            'deductible_amount' => $this->deductible_amount,
            'duration_days' => $this->duration_days,
            'renewal_type' => $this->renewal_type,
            'grace_period_days' => $this->grace_period_days,
            'is_active' => $this->is_active,
            'requires_medical_check' => $this->requires_medical_check,
            'min_age' => $this->min_age,
            'max_age' => $this->max_age,
            'approved_by_admin' => $this->approved_by_admin,
            'approval_date' => $this->approval_date,
            'terms_and_conditions_url' => $this->terms_and_conditions_url,
            
            'provider' => [
                'company_name' => $this->provider->company_name,
                'license_number' => $this->provider->license_number,
            ],
            
            'category' => [
                'name' => $this->category->name,
                'icon_url' => $this->category->icon_url,
            ],
            
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
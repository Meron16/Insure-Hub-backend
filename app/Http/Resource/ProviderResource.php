<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProviderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'provider_id' => $this->provider_id,
            'user_id' => $this->user_id,
            'company_name' => $this->company_name,
            'license_number' => $this->license_number,
            'website_url' => $this->website_url,
            'contact_person' => $this->contact_person,
            'contact_email' => $this->contact_email,
            'contact_phone' => $this->contact_phone,
            'is_approved' => $this->is_approved,
            'approval_date' => $this->approval_date,
            'rating' => $this->rating,
            'years_in_business' => $this->years_in_business,
            'description' => $this->description,
            
            'bank_details' => $this->bankDetails ? [
                'account_name' => $this->bankDetails->account_name,
                'bank_name' => $this->bankDetails->bank_name,
                'is_verified' => $this->bankDetails->is_verified,
            ] : null,
            
            'documents' => $this->documents->map(function ($document) {
                return [
                    'document_type' => $document->document_type,
                    'status' => $document->status,
                    'expiry_date' => $document->expiry_date,
                ];
            }),
            
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
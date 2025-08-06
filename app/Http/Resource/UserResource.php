<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'user_id' => $this->user_id,
            'full_name' => $this->full_name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'role' => $this->role,
            'account_status' => $this->account_status,
            'profile_picture_url' => $this->profile_picture_url,
            'two_factor_enabled' => $this->two_factor_enabled,
            'last_login' => $this->last_login,
            
            'addresses' => $this->addresses->map(function ($address) {
                return [
                    'address_id' => $address->address_id,
                    'address_type' => $address->address_type,
                    'street_address' => $address->street_address,
                    'city' => $address->city,
                    'is_default' => $address->is_default,
                ];
            }),
            
            'provider' => $this->provider ? [
                'company_name' => $this->provider->company_name,
                'license_number' => $this->provider->license_number,
                'is_approved' => $this->provider->is_approved,
            ] : null,
            
            'kyc_status' => $this->kycVerifications->first() ? $this->kycVerifications->first()->status : 'not_submitted',
            
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
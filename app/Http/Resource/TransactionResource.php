<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'transaction_id' => $this->transaction_id,
            'user_id' => $this->user_id,
            'policy_id' => $this->policy_id,
            'user_policy_id' => $this->user_policy_id,
            'amount' => $this->amount,
            'payment_method' => $this->payment_method,
            'transaction_reference' => $this->transaction_reference,
            'status' => $this->status,
            'failure_reason' => $this->failure_reason,
            'invoice_url' => $this->invoice_url,
            
            'user' => [
                'full_name' => $this->user->full_name,
                'email' => $this->user->email,
            ],
            
            'policy' => [
                'title' => $this->policy->title,
                'premium_amount' => $this->policy->premium_amount,
            ],
            
            'user_policy' => [
                'policy_number' => $this->userPolicy->policy_number,
                'status' => $this->userPolicy->status,
            ],
            
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
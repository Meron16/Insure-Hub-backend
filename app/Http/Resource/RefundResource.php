<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RefundResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'refund_id' => $this->refund_id,
            'transaction_id' => $this->transaction_id,
            'amount' => $this->amount,
            'reason' => $this->reason,
            'processed_by' => $this->processed_by,
            'processed_at' => $this->processed_at,
            'status' => $this->status,
            'reference_number' => $this->reference_number,
            
            'transaction' => [
                'amount' => $this->transaction->amount,
                'status' => $this->transaction->status,
                'payment_method' => $this->transaction->payment_method,
            ],
            
            'processor' => [
                'full_name' => $this->processor->full_name,
                'email' => $this->processor->email,
            ],
            
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
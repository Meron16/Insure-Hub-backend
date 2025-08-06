<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SentEmailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'email_id' => $this->email_id,
            'template_id' => $this->template_id,
            'recipient_email' => $this->recipient_email,
            'subject' => $this->subject,
            'body' => $this->body,
            'status' => $this->status,
            'error_message' => $this->error_message,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
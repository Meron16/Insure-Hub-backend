<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SystemSettingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'setting_id' => $this->setting_id,
            'key' => $this->key,
            'value' => $this->value,
            'description' => $this->description,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
        ];
    }
}
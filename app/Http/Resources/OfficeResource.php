<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OfficeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'label'          => $this->label,
            'address'        => $this->address,
            'phone'          => $this->phone,
            'map_url'        => $this->map_url,
            'is_head_office' => $this->is_head_office,
        ];
    }
}

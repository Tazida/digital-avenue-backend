<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EquipmentCategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'slug'  => $this->slug,
            'name'  => $this->name,
            'items' => EquipmentResource::collection($this->whenLoaded('equipment')),
        ];
    }
}

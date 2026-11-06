<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EquipmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'name'      => $this->name,
            'capacity'  => $this->capacity,
            'qty_own'   => $this->qty_own,
            'qty_hire'  => $this->qty_hire,
            'unit'      => $this->unit,
            'condition' => $this->condition,
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StaffRoleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'title'      => $this->title,
            'count_own'  => $this->count_own,
            'count_hire' => $this->count_hire,
        ];
    }
}

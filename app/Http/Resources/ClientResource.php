<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'   => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            // Logos (e.g. UN agencies) are only public once the client confirms permission.
            'logo_url'       => $this->show_logo ? $this->logo_url : null,
            'website'        => $this->website,
            'projects_count' => $this->whenCounted('projects'),
        ];
    }
}

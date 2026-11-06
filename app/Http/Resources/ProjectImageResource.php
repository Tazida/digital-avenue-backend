<?php

namespace App\Http\Resources;

use App\Support\Media;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectImageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'url'     => Media::url($this->image_url),
            'caption' => $this->caption,
        ];
    }
}

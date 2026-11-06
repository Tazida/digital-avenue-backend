<?php

namespace App\Http\Resources;

use App\Support\Text;
use Illuminate\Http\Request;

/**
 * A service page: the list fields plus the full description and its published projects.
 */
class ServiceDetailResource extends ServiceResource
{
    public function toArray(Request $request): array
    {
        return parent::toArray($request) + [
            'description' => Text::paragraphs($this->description),
            'projects'    => ProjectResource::collection($this->whenLoaded('projects')),
        ];
    }
}

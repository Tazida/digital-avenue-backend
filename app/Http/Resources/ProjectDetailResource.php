<?php

namespace App\Http\Resources;

use App\Support\Text;
use Illuminate\Http\Request;

/**
 * A project page: the card fields plus scope of work, exact dates and the photo gallery.
 */
class ProjectDetailResource extends ProjectResource
{
    public function toArray(Request $request): array
    {
        return parent::toArray($request) + [
            'start_date' => $this->start_date?->toDateString(),
            'end_date'   => $this->end_date?->toDateString(),
            'scope'      => Text::lines($this->scope),
            'images'     => ProjectImageResource::collection($this->whenLoaded('images')),
        ];
    }
}

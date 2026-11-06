<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * A project card (Projects list, Home page, service pages).
 * contract_value is only included when the client switched show_value on.
 */
class ProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'slug'   => $this->slug,
            'title'  => $this->title,
            'client' => $this->whenLoaded('client', fn () => [
                'name' => $this->client->name,
                'type' => $this->client->type,
            ]),
            'location'    => $this->location,
            'status'      => $this->status,
            'years'       => $this->yearsLabel(),
            'start_year'  => $this->start_date?->year,
            'end_year'    => $this->end_date?->year,
            'summary'     => $this->summary,
            'is_featured' => $this->is_featured,
            'cover_image' => $this->whenLoaded('coverImage', fn () => $this->coverImage ? new ProjectImageResource($this->coverImage) : null),
            'services'    => $this->whenLoaded('services', fn () => $this->services->map(fn ($s) => [
                'slug'  => $s->slug,
                'title' => $s->title,
            ])->values()),
            'contract_value' => $this->when($this->show_value && $this->contract_value !== null, fn () => $this->contract_value),
        ];
    }
}

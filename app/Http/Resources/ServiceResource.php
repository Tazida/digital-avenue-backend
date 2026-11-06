<?php

namespace App\Http\Resources;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'slug'           => $this->slug,
            'title'          => $this->title,
            'category'       => $this->category,
            'category_label' => Service::CATEGORIES[$this->category] ?? $this->category,
            'summary'        => $this->summary,
            'icon'           => $this->icon,
            'projects_count' => $this->whenCounted('projects'),
        ];
    }
}

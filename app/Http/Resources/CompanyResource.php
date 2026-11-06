<?php

namespace App\Http\Resources;

use App\Support\Media;
use App\Support\Text;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'name'            => $this->name,
            'tagline'         => $this->tagline,
            'intro'           => Text::paragraphs($this->intro),
            'vision'          => $this->vision,
            'mission'         => Text::lines($this->mission),
            'founder_message' => Text::paragraphs($this->founder_message),
            'email'           => $this->email,
            'phone'           => $this->phone,
            'logo_url'        => Media::url($this->logo_url),
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Public part of a licence or enlistment. The certificate number and the scan are
 * private records and are never included.
 */
class CertificationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'title'      => $this->title,
            'issuer'     => $this->issuer,
            'issued_on'  => $this->issued_on?->toDateString(),
            'expires_on' => $this->expires_on?->toDateString(),
        ];
    }
}

<?php

namespace App\Services;

use App\Models\Client;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * Clients shown on the public site (Clients page, Home page client names).
 */
class ClientService
{
    public function list(?string $type = null): Collection
    {
        return Client::query()
            ->when($type, fn (Builder $q) => $q->where('type', $type))
            ->ordered()
            ->get();
    }
}

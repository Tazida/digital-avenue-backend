<?php

namespace App\Services;

use App\Exceptions\NotFoundException;
use App\Models\Service;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * Published areas of operation (the Service model), grouped as on the Services page.
 */
class ServiceCatalogService
{
    /** [{key, label, services: [...]}] in the order of Service::CATEGORIES; empty groups are left out. */
    public function grouped(): Collection
    {
        $services = Service::published()
            ->withCount(['projects' => fn (Builder $q) => $q->where('is_published', true)])
            ->ordered()
            ->get()
            ->groupBy('category');

        return collect(Service::CATEGORIES)
            ->map(fn (string $label, string $key) => [
                'key'      => $key,
                'label'    => $label,
                'services' => $services->get($key, collect()),
            ])
            ->filter(fn (array $group) => $group['services']->isNotEmpty())
            ->values();
    }

    public function findPublished(string $slug): Service
    {
        $service = Service::published()
            ->where('slug', $slug)
            ->with(['projects' => fn ($q) => $q->published()->with(['client', 'coverImage', 'services'])->latestFirst()])
            ->first();

        if (! $service) {
            throw new NotFoundException('Service not found.');
        }

        return $service;
    }
}

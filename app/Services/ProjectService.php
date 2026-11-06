<?php

namespace App\Services;

use App\Exceptions\NotFoundException;
use App\Models\Project;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

/**
 * Published projects for the Projects pages. Unpublished projects behave as if
 * they did not exist (404).
 */
class ProjectService
{
    public const PER_PAGE = 12;

    /**
     * @param  array{service?: ?string, client_type?: ?string, featured?: ?bool}  $filters
     */
    public function list(array $filters, int $perPage = self::PER_PAGE): LengthAwarePaginator
    {
        return Project::published()
            ->with(['client', 'coverImage', 'services'])
            ->when($filters['service'] ?? null, fn (Builder $q, string $slug) => $q->whereHas('services', fn (Builder $s) => $s->where('slug', $slug)))
            ->when($filters['client_type'] ?? null, fn (Builder $q, string $type) => $q->whereHas('client', fn (Builder $c) => $c->where('type', $type)))
            ->when($filters['featured'] ?? null, fn (Builder $q) => $q->where('is_featured', true))
            ->latestFirst()
            ->paginate($perPage);
    }

    public function findPublished(string $slug): Project
    {
        $project = Project::published()
            ->where('slug', $slug)
            ->with(['client', 'services', 'images'])
            ->first();

        if (! $project) {
            throw new NotFoundException('Project not found.');
        }

        return $project;
    }
}

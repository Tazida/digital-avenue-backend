<?php

namespace App\Services;

use App\Models\Client;
use App\Models\Equipment;
use App\Models\Project;
use App\Models\Service;
use App\Models\StaffRole;

/**
 * Everything the Home page needs in one call.
 */
class HomeService
{
    public const FEATURED_LIMIT = 6;

    public function home(): array
    {
        return [
            'stats'    => $this->stats(),
            'featured' => Project::published()
                ->where('is_featured', true)
                ->with(['client', 'coverImage', 'services'])
                ->latestFirst()
                ->limit(self::FEATURED_LIMIT)
                ->get(),
            'services' => Service::published()->ordered()->get(),
            'clients'  => Client::ordered()->get(),
        ];
    }

    /** Key numbers for tender reviewers. */
    private function stats(): array
    {
        return [
            'projects'        => Project::published()->count(),
            'clients'         => Client::count(),
            'staff_own'       => (int) StaffRole::sum('count_own'),
            'staff_hire'      => (int) StaffRole::sum('count_hire'),
            'equipment_types' => Equipment::count(),
        ];
    }
}

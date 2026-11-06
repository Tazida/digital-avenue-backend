<?php

namespace App\Services;

use App\Models\EquipmentCategory;
use App\Models\StaffRole;

/**
 * Equipment lists and workforce for the Capabilities page.
 */
class CapabilityService
{
    public function overview(): array
    {
        $roles = StaffRole::ordered()->get();

        return [
            'equipment' => EquipmentCategory::ordered()->with('equipment')->get(),
            'workforce' => $roles,
            'totals'    => [
                'staff_own'  => (int) $roles->sum('count_own'),
                'staff_hire' => (int) $roles->sum('count_hire'),
            ],
        ];
    }
}

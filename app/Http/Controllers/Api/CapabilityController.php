<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EquipmentCategoryResource;
use App\Http\Resources\StaffRoleResource;
use App\Services\CapabilityService;
use App\Traits\ApiResponse;

/**
 * Capabilities page: equipment lists and workforce.
 */
class CapabilityController extends Controller
{
    use ApiResponse;

    public function __construct(private CapabilityService $capabilities) {}

    public function show()
    {
        return $this->handle(function () {
            $overview = $this->capabilities->overview();

            return $this->success([
                'data' => [
                    'equipment' => EquipmentCategoryResource::collection($overview['equipment']),
                    'workforce' => StaffRoleResource::collection($overview['workforce']),
                    'totals'    => $overview['totals'],
                ],
            ]);
        });
    }
}

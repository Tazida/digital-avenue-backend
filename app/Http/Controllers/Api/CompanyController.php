<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CertificationResource;
use App\Http\Resources\CompanyResource;
use App\Http\Resources\CoreValueResource;
use App\Http\Resources\OfficeResource;
use App\Services\CompanyService;
use App\Traits\ApiResponse;

/**
 * About and Contact pages, site header and footer.
 */
class CompanyController extends Controller
{
    use ApiResponse;

    public function __construct(private CompanyService $company) {}

    public function show()
    {
        return $this->handle(function () {
            $profile = $this->company->profile();

            return $this->success([
                'data' => [
                    'company'        => new CompanyResource($profile['company']),
                    'offices'        => OfficeResource::collection($profile['offices']),
                    'core_values'    => CoreValueResource::collection($profile['core_values']),
                    'certifications' => CertificationResource::collection($profile['certifications']),
                ],
            ]);
        });
    }
}

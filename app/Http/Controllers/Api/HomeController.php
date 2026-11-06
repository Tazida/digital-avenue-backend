<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ClientResource;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\ServiceResource;
use App\Services\HomeService;
use App\Traits\ApiResponse;

/**
 * Home page: key numbers, featured projects, services and clients in one call.
 */
class HomeController extends Controller
{
    use ApiResponse;

    public function __construct(private HomeService $home) {}

    public function show()
    {
        return $this->handle(function () {
            $home = $this->home->home();

            return $this->success([
                'data' => [
                    'stats'    => $home['stats'],
                    'featured' => ProjectResource::collection($home['featured']),
                    'services' => ServiceResource::collection($home['services']),
                    'clients'  => ClientResource::collection($home['clients']),
                ],
            ]);
        });
    }
}

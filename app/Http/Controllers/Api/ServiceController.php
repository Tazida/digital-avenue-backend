<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceDetailResource;
use App\Http\Resources\ServiceResource;
use App\Services\ServiceCatalogService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

/**
 * Services pages: the grouped list and one service with its projects.
 */
class ServiceController extends Controller
{
    use ApiResponse;

    public function __construct(private ServiceCatalogService $services) {}

    public function list()
    {
        return $this->handle(fn () => $this->success([
            'data' => $this->services->grouped()->map(fn (array $group) => [
                'key'      => $group['key'],
                'label'    => $group['label'],
                'services' => ServiceResource::collection($group['services']),
            ]),
        ]));
    }

    public function show(Request $request)
    {
        return $this->handle(fn () => $this->success([
            'data' => new ServiceDetailResource($this->services->findPublished((string) $request->route('slug'))),
        ]));
    }
}

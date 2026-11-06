<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClientListRequest;
use App\Http\Resources\ClientResource;
use App\Services\ClientService;
use App\Traits\ApiResponse;

/**
 * Clients for the public site. Read-only: clients are edited in the Filament admin panel.
 */
class ClientController extends Controller
{
    use ApiResponse;

    public function __construct(private ClientService $clients) {}

    public function list(ClientListRequest $request)
    {
        return $this->handle(fn () => $this->success([
            'data' => ClientResource::collection($this->clients->list($request->validated('type'))),
        ]));
    }
}

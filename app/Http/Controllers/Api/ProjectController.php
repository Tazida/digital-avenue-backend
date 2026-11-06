<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectListRequest;
use App\Http\Resources\ProjectDetailResource;
use App\Http\Resources\ProjectResource;
use App\Services\ProjectService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

/**
 * Projects pages: filtered, paginated list and one project with its gallery.
 */
class ProjectController extends Controller
{
    use ApiResponse;

    public function __construct(private ProjectService $projects) {}

    public function list(ProjectListRequest $request)
    {
        return $this->handle(function () use ($request) {
            $page = $this->projects->list(
                $request->filters(),
                (int) ($request->validated('per_page') ?? ProjectService::PER_PAGE),
            );

            return $this->success([
                'data' => ProjectResource::collection($page->items()),
                'meta' => [
                    'current_page' => $page->currentPage(),
                    'last_page'    => $page->lastPage(),
                    'per_page'     => $page->perPage(),
                    'total'        => $page->total(),
                ],
            ]);
        });
    }

    public function show(Request $request)
    {
        return $this->handle(fn () => $this->success([
            'data' => new ProjectDetailResource($this->projects->findPublished((string) $request->route('slug'))),
        ]));
    }
}

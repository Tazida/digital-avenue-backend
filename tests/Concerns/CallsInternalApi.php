<?php

namespace Tests\Concerns;

use Illuminate\Testing\TestResponse;

/**
 * Calls /api/v1.0/... with the internal token, as the SvelteKit server does.
 */
trait CallsInternalApi
{
    protected const API_TOKEN = 'test-internal-token';

    protected function setUpInternalApi(): void
    {
        config(['services.frontend.token' => self::API_TOKEN]);
    }

    protected function api(string $method, string $path, array $data = [], array $headers = []): TestResponse
    {
        return $this->json($method, '/api/v1.0/'.ltrim($path, '/'), $data, ['X-Internal-Token' => self::API_TOKEN] + $headers);
    }
}

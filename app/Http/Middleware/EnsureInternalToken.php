<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * The API is internal: only the SvelteKit server calls it, over the Docker network.
 * As a second lock, every call must send the shared token in the X-Internal-Token header.
 * With no token configured, every call is refused (fail closed).
 */
class EnsureInternalToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $expected = (string) config('services.frontend.token');
        $given = (string) $request->header('X-Internal-Token');

        if ($expected === '' || ! hash_equals($expected, $given)) {
            return response()->json([
                'error'   => true,
                'message' => 'Unauthorized.',
            ], \ResponseCode['Unauthorized']);
        }

        return $next($request);
    }
}

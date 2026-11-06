<?php

namespace App\Support;

use Illuminate\Http\Request;

/**
 * The API is called by the SvelteKit server, so $request->ip() is the server's address.
 * SvelteKit forwards the visitor's address in X-Visitor-IP; it is trusted because only
 * callers with the internal token reach the API.
 */
class VisitorIp
{
    public static function from(Request $request): string
    {
        $forwarded = (string) $request->header('X-Visitor-IP');

        return filter_var($forwarded, FILTER_VALIDATE_IP) ? $forwarded : (string) $request->ip();
    }
}

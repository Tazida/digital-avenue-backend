<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Cloudflare Turnstile check for the inquiry form (the website sends the widget's token).
 *
 * With no secret configured the check is skipped in local and testing only; anywhere
 * else it fails closed, so a missing key can never switch spam protection off silently.
 */
class TurnstileService
{
    private const VERIFY_URL = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';

    public function verify(?string $token, ?string $visitorIp = null): bool
    {
        $secret = (string) config('services.turnstile.secret');

        if ($secret === '') {
            return app()->environment(['local', 'testing']);
        }

        if (blank($token)) {
            return false;
        }

        try {
            $response = Http::asForm()->timeout(5)->post(self::VERIFY_URL, array_filter([
                'secret'   => $secret,
                'response' => $token,
                'remoteip' => $visitorIp,
            ]));

            return $response->ok() && $response->json('success') === true;
        } catch (\Throwable $e) {
            Log::warning('Turnstile check failed: '.$e->getMessage());

            return false;
        }
    }
}

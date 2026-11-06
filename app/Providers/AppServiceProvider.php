<?php

namespace App\Providers;

use App\Support\VisitorIp;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Inquiry form: per visitor, 3 a minute and 20 a day.
        RateLimiter::for('inquiries', function (Request $request) {
            $ip = VisitorIp::from($request);
            $tooMany = fn () => response()->json([
                'error'   => true,
                'message' => 'Too many messages. Please try again later.',
            ], \ResponseCode['Too Many Requests']);

            return [
                Limit::perMinute(3)->by('inquiries-minute:'.$ip)->response($tooMany),
                Limit::perDay(20)->by('inquiries-day:'.$ip)->response($tooMany),
            ];
        });
    }
}

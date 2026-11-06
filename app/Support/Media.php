<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

/**
 * Public URL of a file uploaded in the admin panel (stored on the "public" disk).
 */
class Media
{
    public static function url(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        return Storage::disk('public')->url($path);
    }
}

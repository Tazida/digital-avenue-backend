<?php

namespace App\Support;

/**
 * Turns the plain text typed in the admin panel into lists the website can render.
 */
class Text
{
    /** Paragraphs are separated by an empty line. */
    public static function paragraphs(?string $text): array
    {
        if (blank($text)) {
            return [];
        }

        $parts = preg_split('/\R\s*\R/', trim($text));

        return array_values(array_filter(array_map(fn (string $p) => trim(preg_replace('/\s*\R\s*/', ' ', $p)), $parts)));
    }

    /** One item per line (mission points, scope of work). */
    public static function lines(?string $text): array
    {
        if (blank($text)) {
            return [];
        }

        return array_values(array_filter(array_map('trim', preg_split('/\R/', $text))));
    }
}

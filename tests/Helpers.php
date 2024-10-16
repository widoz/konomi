<?php

declare(strict_types=1);

function fixturesDirectory(): string
{
    return __DIR__ . '/fixtures/php';
}

/* -------------------------------------------------------------------------------------------------
 *  WordPress Helpers
 * ---------------------------------------------------------------------------------------------- */
if (!function_exists('wp_normalize_path')) {
    function wp_normalize_path(string $path): string
    {
        return str_replace('\\', '/', $path);
    }
}

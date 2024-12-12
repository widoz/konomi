<?php

declare(strict_types=1);

// phpcs:disable Inpsyde.CodeQuality.NoRootNamespaceFunctions.Found

function stubsDirectory(): string
{
    return __DIR__ . '/stubs';
}

function fixturesDirectory(): string
{
    return __DIR__ . '/fixtures/php';
}

function wordPressDirPath(): string
{
    return dirname(__DIR__) . '/vendor/roots/wordpress-no-content';
}

function setUpHooks(): void
{
    require_once wordPressDirPath() . '/wp-includes/plugin.php';
    if (!class_exists(\WP_Hook::class)) {
        throw new \RuntimeException('Cannot locate WP_Hook class');
    }
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

/* -------------------------------------------------------------------------------------------------
 *  Integration Helpers
 * ---------------------------------------------------------------------------------------------- */

function includeValidPostUserLikes(): array
{
    return include stubsDirectory() . '/php/valid-post-user-likes.php';
}

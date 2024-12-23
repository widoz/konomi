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

function includeValidUsersLikes(): array
{
    return include stubsDirectory() . '/php/valid-users-likes.php';
}

/**
 * @return array{0: callable, 1: callable, 2: callable, 3: callable}
 */
function setupUserMetaStorage(array &$data): array
{
    $stubsCounter = [
        'get_user_meta' => 0,
        'update_user_meta' => 0,
    ];

    return [
        static function () use (&$stubsCounter): array {
            return $stubsCounter;
        },
        static function (int $entityId, string $key, bool $single) use (&$data, &$stubsCounter): array {
            $stubsCounter['get_user_meta']++;
            return $data[$entityId][$key] ?? [];
        },
        static function (int $entityId, string $key, array $newData) use (&$data, &$stubsCounter): bool {
            $stubsCounter['update_user_meta']++;
            $data[$entityId][$key] = $newData;
            return true;
        },
    ];
}

/**
 * @return array{0: callable, 1: callable, 2: callable, 3: callable}
 */
function setupPostMetaStorage(array &$data): array
{
    $stubsCounter = [
        'get_post_meta' => 0,
        'update_post_meta' => 0,
    ];

    return [
        static function () use (&$stubsCounter): array {
            return $stubsCounter;
        },
        static function (int $entityId, string $key, bool $single) use (&$data, &$stubsCounter): array {
            $stubsCounter['get_post_meta']++;
            return $data[$entityId][$key] ?? [];
        },
        static function (int $entityId, string $key, array $newData) use (&$data, &$stubsCounter): bool {
            $stubsCounter['update_post_meta']++;
            $data[$entityId][$key] = $newData;
            return true;
        },
    ];
}

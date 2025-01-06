<?php

declare(strict_types=1);

use Inpsyde\Modularity\Properties;

// phpcs:disable Inpsyde.CodeQuality.NoRootNamespaceFunctions.Found

function projectRootDirectory(): string
{
    return dirname(__DIR__);
}

function stubsDirectory(): string
{
    return __DIR__ . '/stubs';
}

function fixturesDirectory(): string
{
    return __DIR__ . '/fixtures/php';
}

/* -------------------------------------------------------------------------------------------------
 *  Mock Helpers
 * ---------------------------------------------------------------------------------------------- */

function propertiesMock(): \Mockery\MockInterface&Properties\PluginProperties
{
    return \Mockery::mock(Properties\PluginProperties::class, [
        'baseUrl' => 'http://example.com',
        'basePath' => projectRootDirectory(),
        'version' => '1.0.0',
    ]);
}

/* -------------------------------------------------------------------------------------------------
 *  WordPress Helpers & Stubs
 * ---------------------------------------------------------------------------------------------- */

function wordPressDirPath(): string
{
    return dirname(__DIR__) . '/vendor/roots/wordpress-no-content';
}

function setupWpConstants(): void
{
    if (!defined('ABSPATH')) {
        define('ABSPATH', wordPressDirPath() . '/');
    }

    if (!defined('WPINC')) {
        define('WPINC', '/wp-includes');
    }
}

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

/* -------------------------------------------------------------------------------------------------
 *  Functional Helpers
 * ---------------------------------------------------------------------------------------------- */

function setUpHooks(): void
{
    require_once wordPressDirPath() . '/wp-includes/plugin.php';
}

function setUpWpError(): void
{
    require_once wordPressDirPath() . '/wp-includes/class-wp-error.php';
}

function setUpWpRest(): void
{

    require_once wordPressDirPath() . '/wp-includes/class-wp-http-response.php';
    require_once wordPressDirPath() . '/wp-includes/rest-api/class-wp-rest-request.php';
    require_once wordPressDirPath() . '/wp-includes/rest-api/class-wp-rest-response.php';
}

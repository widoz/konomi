<?php

declare(strict_types=1);

// phpcs:disable PSR1.Files. SideEffects. FoundWithSymbols
// phpcs:disable Inpsyde.CodeQuality.NoRootNamespaceFunctions.Found

if (!isset($GLOBALS['container'])) {
    $GLOBALS['container'] = [
        'modules' => [
            'registered' => [],
            'enqueued' => [],
        ],
        'scripts' => [
            'registered' => [],
            'enqueued' => [],
        ],
        'styles' => [
            'registered' => [],
            'enqueued' => [],
        ],
    ];
}

function asset(string $handle, string $what, string $status): array
{
    return $GLOBALS['container'][$what][$status][$handle] ?? [];
}

if (!function_exists('wp_add_inline_script')) {
    function wp_add_inline_script(string $handle, string $data, string $position = 'after'): void
    {
        $GLOBALS['container']['scripts']['inline'][$handle] = [
            'position' => $position,
            'data' => $data,
        ];
    }
}

if (!function_exists('wp_register_script_module')) {
    function wp_register_script_module(string $handle, string $src, array $dependencies, string $version): void
    {
        $GLOBALS['container']['modules']['registered'][$handle] = [
            'src' => $src,
            'dependencies' => $dependencies,
            'version' => $version,
        ];
    }
}

if (!function_exists('wp_register_script')) {
    function wp_register_script(string $handle, string $src, array $dependencies, string $version): void
    {
        $GLOBALS['container']['scripts']['registered'][$handle] = [
            'src' => $src,
            'dependencies' => $dependencies,
            'version' => $version,
        ];
    }
}

if (!function_exists('wp_enqueue_script')) {
    function wp_enqueue_script(string $handle): void
    {
        $GLOBALS['container']['scripts']['enqueued'][$handle] = $handle;
    }
}

if (!function_exists('wp_script_is')) {
    function wp_script_is(string $handle, string $what): bool
    {
        return array_key_exists($handle, $GLOBALS['container']['scripts'][$what]);
    }
}

if (!function_exists('wp_style_is')) {
    function wp_style_is(string $handle, string $what): bool
    {
        return array_key_exists($handle, $GLOBALS['container']['styles'][$what]);
    }
}

if (!function_exists('wp_module_script_is')) {
    function wp_module_script_is(string $handle, string $what): bool
    {
        return array_key_exists($handle, $GLOBALS['container']['modules'][$what]);
    }
}

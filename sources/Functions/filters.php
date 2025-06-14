<?php

declare(strict_types=1);

namespace SpaghettiDojo\Konomi\Functions;

/**
 * @api
 */
function add_action_on_module_import(string $moduleName, callable $action): void
{
    add_single_conditional_filter(
        'wp_inline_script_attributes',
        static fn (array $attributes, string $data) => array_key_exists('type', $attributes) &&
            $attributes['type'] === 'importmap' &&
            str_contains($data, $moduleName),
        static function (array $attributes) use ($action): array {
            $action();
            return $attributes;
        }
    );
}

/**
 * @api
 */
function add_single_conditional_filter(string $name, callable $condition, callable $callback): void
{
    $filterCallback = static function (mixed ...$args) use (
        $name,
        &$filterCallback,
        $condition,
        $callback,
    ): mixed {

        if (!$condition(...$args)) {
            return $args[0] ?? null;
        }

        remove_filter($name, $filterCallback);
        return $callback(...$args);
    };

    add_filter($name, $filterCallback, 10, PHP_INT_MAX);
}

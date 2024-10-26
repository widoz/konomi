<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Utils;

class ConditionalRemovableImportMapAwareHook
{
    final private function __construct()
    {
    }

    public static function do(callable $condition, callable $callback): void
    {
        ConditionalRemovableHook::add(
            'wp_inline_script_attributes',
            static function (
                ConditionalRemovableHook $that,
                array $attributes,
                string $data
            ) use (
                $condition,
                $callback
            ): array {
                if (!array_key_exists('type', $attributes) || $attributes['type'] !== 'importmap') {
                    return $attributes;
                }

                if ($condition($attributes, $data)) {
                    $callback();
                    $that->remove();
                }

                return $attributes;
            },
            10,
            2
        );
    }
}

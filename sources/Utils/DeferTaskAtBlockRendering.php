<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Utils;

class DeferTaskAtBlockRendering
{
    private static int $priority = 0;

    final private function __construct()
    {
    }

    public static function do(callable $callback, mixed ...$args): void
    {
        self::$priority += 20;

        ConditionalRemovableHook::filter(
            'pre_render_block',
            static function (
                ConditionalRemovableHook $that,
                mixed $nullish,
                array $block
            ) use (
                $callback,
                $args
            ): mixed {
                if (str_contains('konomi/', $block['blockName'] ?? '')) {
                    $that->remove();
                    $callback(...$args);
                }
                return $nullish;
            },
            self::$priority,
            2
        );
    }
}

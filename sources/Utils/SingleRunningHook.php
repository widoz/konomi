<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Utils;

class SingleRunningHook
{
    final private function __construct() {}

    public static function action(
        string $name,
        callable $callback,
        int $priority = 10,
        int $arguments = 1
    ): void {

        self::makeHook($name, 'action', $callback, $priority, $arguments);
    }

    public static function filter(
        string $name,
        callable $callback,
        int $priority = 10,
        int $arguments = 1
    ): void {

        self::makeHook($name, 'filter', $callback, $priority, $arguments);
    }

    private static function makeHook(
        string $name,
        string $type,
        callable $callback,
        int $priority = 10,
        int $arguments = 1
    ): void {

        ConditionalRemovableHook::{$type}(
            $name,
            static function (
                ConditionalRemovableHook $that,
                mixed ...$args
            ) use (
                $callback
            ): mixed {
                $that->remove();
                return $callback(...$args);
            },
            $priority,
            $arguments
        );
    }
}

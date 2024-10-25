<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Utils;

class ConditionalRemovableHook
{
    private string $name = '';

    /**
     * @var callable|null
     */
    private $callback = null;

    private int $priority = 10;

    public static function add(
        string $name,
        callable $callback,
        int $priority = 10,
        int $arguments = 1
    ): ConditionalRemovableHook {

        return self::makeHook($name, $callback, $priority, $arguments);
    }

    private static function makeHook(
        string $name,
        callable $callback,
        int $priority = 10,
        int $arguments = 1
    ): ConditionalRemovableHook {

        $instance = new self();

        $instance->name = $name;
        $instance->priority = $priority;
        $instance->callback = static fn (mixed ...$args): mixed => $callback($instance, ...$args);

        add_filter(
            $instance->name,
            $instance->callback,
            $instance->priority,
            $arguments
        );

        return $instance;
    }

    final private function __construct()
    {
    }

    public function remove(): void
    {
        remove_filter($this->name, $this->callback, $this->priority);
    }
}

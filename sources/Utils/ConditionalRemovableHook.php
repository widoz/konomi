<?php
declare(strict_types=1);

namespace Widoz\Wp\Konomi\Utils;

class ConditionalRemovableHook
{
    public const TYPE_ACTION = 'action';
    public const TYPE_FILTER = 'filter';

    private string $name = '';

    private ?string $type = null;

    /**
     * @var callable|null
     */
    private $callback = null;

    private int $priority = 10;

    public static function action(
        string $name,
        callable $callback,
        int $priority = 10,
        int $arguments = 1
    ): ConditionalRemovableHook {

        return self::makeHook($name, self::TYPE_ACTION, $callback, $priority, $arguments);
    }

    public static function filter(
        string $name,
        callable $callback,
        int $priority = 10,
        int $arguments = 1
    ): ConditionalRemovableHook {

        return self::makeHook($name, self::TYPE_FILTER, $callback, $priority, $arguments);
    }

    private static function makeHook(
        string $name,
        string $type,
        callable $callback,
        int $priority = 10,
        int $arguments = 1
    ): ConditionalRemovableHook {

        $instance = new self();

        $instance->name = $name;
        $instance->type = $type;
        $instance->priority = $priority;
        $instance->callback = static fn (mixed ...$args) => $callback($instance, ...$args);
        $hook = $instance->type === self::TYPE_ACTION
            ? 'add_action'
            : 'add_filter';

        $hook(
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
        if (!$this->callback || !$this->priority) {
            return;
        }

        if ($this->type === self::TYPE_ACTION) {
            \remove_action($this->name, $this->callback, $this->priority);
        }
        if ($this->type === self::TYPE_FILTER) {
            \remove_filter($this->name, $this->callback, $this->priority);
        }
    }
}

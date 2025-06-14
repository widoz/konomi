<?php

declare(strict_types=1);

namespace SpaghettiDojo\Konomi\Blocks;

/**
 * @internal
 */
class InstanceId
{
    private int|null $instanceId = null;

    public static function new(): InstanceId
    {
        return new self();
    }

    final private function __construct()
    {
    }

    public function current(): int
    {
        if ($this->instanceId === null) {
            $this->instanceId = 1;
        }

        return $this->instanceId;
    }

    public function reset(): void
    {
        ++$this->instanceId;
    }
}

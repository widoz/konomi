<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Blocks;

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
            ++$this->instanceId;
        }

        return $this->instanceId;
    }

    public function reset(): void
    {
        $this->instanceId = null;
    }
}

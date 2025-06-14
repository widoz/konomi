<?php

declare(strict_types=1);

namespace SpaghettiDojo\Konomi\Blocks;

interface Context
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(): array;

    public function instanceId(): InstanceId;
}

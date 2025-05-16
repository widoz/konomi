<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Blocks;

interface Context
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(): array;

    public function instanceId(): InstanceId;

    public function isUserLoggedIn(): bool;

    public function postType(): string;

    public function postId(): int;
}

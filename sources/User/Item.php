<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\User;

interface Item
{
    public function id(): int;

    public function type(): string;
}

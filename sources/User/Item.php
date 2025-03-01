<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\User;

/**
 * @api
 */
interface Item
{
    public function id(): int;

    public function type(): string;

    public function isActive(): bool;

    public function isValid(): bool;
}

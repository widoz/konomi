<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\User;

/**
 * @internal
 */
class NullItem implements Item
{
    public static function new(): self
    {
        return new self();
    }

    final private function __construct()
    {
    }

    public function id(): int
    {
        return 0;
    }

    public function type(): string
    {
        return '';
    }

    public function isActive(): bool
    {
        return false;
    }

    public function isValid(): bool
    {
        return false;
    }
}

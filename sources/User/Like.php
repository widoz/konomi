<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\User;

class Like implements Item
{
    public static function new(int $id, string $type, bool $isActive): self
    {
        return new self($id, $type, $isActive);
    }

    final private function __construct(
        private readonly int $id,
        private readonly string $type,
        private readonly bool $isActive
    ) {
    }

    public function id(): int
    {
        return $this->id;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function isValid(): bool
    {
        return $this->id > 0 && $this->type !== '';
    }
}

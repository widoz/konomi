<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\User;

/**
 * @api
 * TODO Move Serialize in here?
 */
class Item
{
    public static function null(): self
    {
        return self::new(0, '', false);
    }

    public static function new(int $id, string $type, bool $isActive, ItemGroup $group = ItemGroup::REACTION): self
    {
        return new self($id, $type, $isActive, $group);
    }

    final private function __construct(
        private readonly int $id,
        private readonly string $type,
        private readonly bool $isActive,
        private readonly ItemGroup $group,
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

    public function group(): ItemGroup
    {
        return $this->group;
    }
}

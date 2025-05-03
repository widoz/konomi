<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\User;

/**
 * @internal
 */
class ItemRegistryKey
{
    public static function new(User $user, ItemGroup $group): self
    {
        return new self($user, $group);
    }

    final private function __construct(
        private readonly User $user,
        private readonly ItemGroup $group,
    ) {
    }

    public function value(): string
    {
        return $this->user->id() . '//' . $this->group->value;
    }
}

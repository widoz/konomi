<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\User;

/**
 * @internal
 */
class ItemRegistryKey implements \Stringable
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

    public function __toString(): string
    {
        $userId = $this->user->id();
        $groupValue = $this->group->value;

        if (empty($userId)) {
            throw new \InvalidArgumentException('User ID cannot be empty');
        }

        if (empty($groupValue)) {
            throw new \InvalidArgumentException('Group value cannot be empty');
        }

        $key = preg_replace(
            '/[^a-z0-9.]/',
            '',
            $userId . '.' . $groupValue
        );

        if (empty($key)) {
            throw new \RuntimeException('Registry key cannot be empty after sanitization');
        }

        return $key;
    }
}

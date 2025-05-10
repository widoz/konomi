<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\User;

/**
 * @internal
 */
class ItemRegistryKey
{
    public static function new(): self
    {
        return new self();
    }

    final private function __construct()
    {
    }

    public function for(User $user, ItemGroup $group): string
    {
        $userId = $user->id();
        $groupValue = $group->value;

        if (!$userId || !$groupValue) {
            return '';
        }

        return (string) preg_replace('/[^a-z0-9.]/', '', $userId . '.' . $groupValue);
    }
}

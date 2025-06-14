<?php

declare(strict_types=1);

namespace SpaghettiDojo\Konomi\User;

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

        if (!$userId) {
            throw new \UnexpectedValueException(
                'Item Registry Key cannot be generated with empty user ID value'
            );
        }

        return (string) preg_replace('/[^a-z0-9.]/', '', $userId . '.' . $groupValue);
    }
}

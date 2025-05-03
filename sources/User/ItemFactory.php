<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\User;

/**
 * @api
 */
class ItemFactory
{
    public static function new(): self
    {
        return new self();
    }

    final private function __construct()
    {
    }

    public function create(
        int $id,
        string $type,
        bool $isActive,
        ItemGroup|string $group
    ): Item {

        $group = ItemGroup::fromValue($group);
        return Item::new($id, $type, $isActive, $group);
    }
}

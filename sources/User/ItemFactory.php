<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\User;

/**
 * @api
 */
class ItemFactory
{
    public static function new(): ItemFactory
    {
        return new self();
    }

    final private function __construct()
    {
    }

    public function create(int $id, string $type, bool $isActive): Item
    {
        return Like::new($id, $type, $isActive);
    }
}

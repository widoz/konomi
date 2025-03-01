<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\User;

/**
 * @api
 */
interface ItemFactory
{
    public function create(int $id, string $type, bool $isActive): Item;
}

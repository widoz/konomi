<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\User;

interface ItemFactory
{
    /**
     * @param non-negative-int $id
     */
    public function create(int $id, string $type, bool $isActive): Item;
}

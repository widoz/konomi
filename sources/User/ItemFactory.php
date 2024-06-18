<?php
declare(strict_types=1);

namespace Widoz\Wp\Konomi\User;

interface ItemFactory
{
    public function create(int $id, string $type): Item;
}

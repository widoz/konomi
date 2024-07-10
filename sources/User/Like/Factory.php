<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\User\Like;

use Widoz\Wp\Konomi\User;

class Factory implements User\ItemFactory
{
    public static function new(): Factory
    {
        return new self();
    }

    final private function __construct()
    {
    }

    public function create(int $id, string $type, bool $isActive): Like
    {
        return Like::new($id, $type, $isActive);
    }
}

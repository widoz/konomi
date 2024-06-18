<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\User\Likes;

use Widoz\Wp\Konomi\User;

class LikeFactory implements User\ItemFactory
{
    public static function new(): LikeFactory
    {
        return new self();
    }

    final private function __construct() {}

    public function create(int $id, string $type): Like
    {
        return Like::new($id, $type);
    }
}

<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Post;

use Widoz\Wp\Konomi\User;

/**
 * @api
 */
class Post
{
    public static function new(Repository $repository): Post
    {
        return new self($repository);
    }

    final private function __construct(readonly private Repository $repository)
    {
    }

    public function countForPost(int $id, User\ItemGroup $group): int
    {
        return count($this->repository->find($id, $group));
    }
}

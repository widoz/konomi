<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Post;

/**
 * TODO Need a Factory and align the implementation to User. The intend is to work with objects
 *      we can share among the code base reducing the amount of data we need to pass around.
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

    public function countForPost(int $id): int
    {
        return array_reduce(
            $this->repository->find($id),
            static fn (int $counter, array $items) => $counter + count($items),
            0
        );
    }
}

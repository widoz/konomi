<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Post;

/**
 * TODO Need a Factory and align the implementation to User. The intend is to work with objects
 *      we can share among the code base reducing the amount of data we need to pass around.
 */
class Post
{
    public static function new(Collection $collection): Post
    {
        return new self($collection);
    }

    final private function __construct(readonly private Collection $collection)
    {
    }

    public function countForPost(int $id): int
    {
        return array_reduce(
            $this->collection->find($id),
            static fn (int $counter, array $items) => $counter + count($items),
            0
        );
    }
}

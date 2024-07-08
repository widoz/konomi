<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\User;

/**
 * @api
 */
class User
{
    public static function new(int $id, Collection $collection): User
    {
        return new self($id, $collection);
    }

    final private function __construct(
        readonly private int $id,
        readonly private Collection $collection
    )
    {
    }

    public function isLoggedIn(): bool
    {
        return get_current_user_id() === $this->id();
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function findLike(int $id): Item
    {
        if (!$this->isLoggedIn()) {
            return NullItem::new();
        }

        return $this->collection->find($this, '_likes', $id);
    }

    public function saveLike(Item $item): bool
    {
        if (!$this->isLoggedIn()) {
            return false;
        }

        return $this->collection->save($this, '_likes', $item);
    }
}

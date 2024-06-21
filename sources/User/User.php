<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\User;

/**
 * @api
 */
class User
{
    public static function new(Collection $collection): User
    {
        return new self($collection);
    }

    final private function __construct(readonly private Collection $collection)
    {
    }

    public function isLoggedIn(): bool
    {
        return is_user_logged_in();
    }

    public function id(): ?int
    {
       return get_current_user_id() ?: null;
    }

    public function findLike(int $id): ?Item
    {
        if (!$this->isLoggedIn()) {
            return null;
        }

        return $this->collection->find($id);
    }
}

<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\User;

/**
 * @api
 */
interface User
{
    public function isLoggedIn(): bool;

    public function id(): ?int;

    public function findLike(int $id): Item;

    public function saveLike(Item $item): bool;
}

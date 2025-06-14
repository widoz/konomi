<?php

declare(strict_types=1);

namespace SpaghettiDojo\Konomi\User;

/**
 * @api
 */
interface User
{
    public function isLoggedIn(): bool;

    public function id(): int;

    public function findItem(int $id, ItemGroup $group): Item;

    public function saveItem(Item $item): bool;
}

<?php

declare(strict_types=1);

namespace SpaghettiDojo\Konomi\User;

/**
 * @internal
 */
class CurrentUser implements User
{
    public static function new(Repository $itemRepository): CurrentUser
    {
        return new self(wp_get_current_user(), $itemRepository);
    }

    final private function __construct(
        readonly private ?\WP_User $user,
        readonly private Repository $itemRepository
    ) {
    }

    public function isLoggedIn(): bool
    {
        return is_user_logged_in();
    }

    public function id(): int
    {
        return $this->user->ID ?? 0;
    }

    public function findItem(int $id, ItemGroup $group): Item
    {
        return $this->itemRepository->find($this, $id, $group);
    }

    public function saveItem(Item $item): bool
    {
        return $this->itemRepository->save($this, $item);
    }
}

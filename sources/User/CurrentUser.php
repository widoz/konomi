<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\User;

/**
 * @api
 */
class CurrentUser implements User
{
    public static function new(Repository $likeRepository): CurrentUser
    {
        return new self(wp_get_current_user(), $likeRepository);
    }

    final private function __construct(
        readonly private ?\WP_User $user,
        readonly private Repository $likeRepository
    ) {
    }

    public function isLoggedIn(): bool
    {
        return is_user_logged_in();
    }

    public function id(): int
    {
        return $this->user?->ID ?? 0;
    }

    public function findLike(int $id): Item
    {
        return $this->likeRepository->find($this, $id);
    }

    public function saveLike(Item $item): bool
    {
        return $this->likeRepository->save($this, $item);
    }
}

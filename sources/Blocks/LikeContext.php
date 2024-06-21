<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Blocks;

use Widoz\Wp\Konomi\User;

class LikeContext
{
    public static function new(User\User $user): LikeContext
    {
        return new self($user);
    }

    final private function __construct(readonly private User\User $user) {}

    public function serialize(): string
    {
        $id = get_the_ID();
        $type = get_post_type($id);

        return wp_json_encode([
            'entityId' => $id,
            'entityType' => $type,
            'isActive' => !!$this->user->findLike($id),
            'userId' => $this->user->id(),
        ]) ?: '';
    }
}

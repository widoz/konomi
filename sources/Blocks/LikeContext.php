<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Blocks;

use Widoz\Wp\Konomi\Post;
use Widoz\Wp\Konomi\User;

class LikeContext
{
    private int $instanceId = 0;

    public static function new(User\User $user, Post\Post $post): LikeContext
    {
        return new self($user, $post);
    }

    final private function __construct(
        readonly private User\User $user,
        readonly private Post\Post $post
    ) {
    }

    public function generate(): array
    {
        $id = (int) get_the_ID();
        $type = (string) get_post_type($id);
        $like = $this->user->findLike($id);

        return [
            'count' => $this->post->countForPost($id),
            'id' => $id,
            // There might be a mismatch for some reason, e.g. someone changed the value in the database.
            'type' => $like->type() ?: $type,
            'isActive' => $like->isActive(),
        ];
    }

    public function instanceId(): int
    {
        return ++$this->instanceId;
    }
}

<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Blocks\Like;

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

    public function toArray(): array
    {
        $like = $this->like();

        return [
            'count' => $this->count(),
            'id' => $this->postId(),
            'isUserLoggedIn' => $this->isUserLoggedIn(),
            'type' => $this->type(),
            'isActive' => $like->isActive(),
        ];
    }

    public function isUserLoggedIn(): bool
    {
        return $this->user->isLoggedIn();
    }

    public function isActive(): bool
    {
        return $this->user->findLike($this->postId())->isActive();
    }

    public function type(): string
    {
        return $this->like()->type() ?: (string) get_post_type($this->postId());
    }

    public function count(): int
    {
        return $this->post->countForPost($this->postId());
    }

    public function postId(): int
    {
        return (int) get_the_ID();
    }

    public function instanceId(): int
    {
        return ++$this->instanceId;
    }

    private function like(): User\Item
    {
        return $this->user->findLike($this->postId());
    }
}

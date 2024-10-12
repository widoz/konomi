<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Blocks\Like;

use Widoz\Wp\Konomi\Post;
use Widoz\Wp\Konomi\User;

// TODO Think about a way to encapsulate the Array
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
            'id' => $this->postId(),
            'type' => $this->postType(),
            'count' => $this->count(),
            'isUserLoggedIn' => $this->isUserLoggedIn(),
            'isActive' => $like->isActive(),
        ];
    }

    public function isUserLoggedIn(): bool
    {
        return $this->user->isLoggedIn();
    }

    public function postType(): string
    {
        return (string) get_post_type($this->postId());
    }

    public function postId(): int
    {
        return (int) get_the_ID();
    }

    public function count(): int
    {
        return $this->post->countForPost($this->postId());
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

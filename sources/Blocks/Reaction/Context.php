<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Blocks\Reaction;

use Widoz\Wp\Konomi\Post;
use Widoz\Wp\Konomi\User;
use Widoz\Wp\Konomi\Blocks;

/**
 * @internal
 */
class Context implements Blocks\Context
{
    use Blocks\PostContextTrait;
    use Blocks\UserContextTrait;

    public static function new(
        User\UserFactory $userFactory,
        Post\Post $post,
        Blocks\InstanceId $instanceId
    ): Context {

        return new self($userFactory, $post, $instanceId);
    }

    final private function __construct(
        readonly private User\UserFactory $userFactory,
        readonly private Post\Post $post,
        readonly private Blocks\InstanceId $instanceId
    ) {
    }

    /**
     * @return array{
     *     count: int,
     *     isActive: bool
     * }
     */
    public function toArray(): array
    {
        $reaction = $this->reaction();

        return [
            'count' => $this->count(),
            'isActive' => $reaction->isActive(),
        ];
    }

    public function instanceId(): Blocks\InstanceId
    {
        return $this->instanceId;
    }

    private function count(): int
    {
        return $this->post->countForPost($this->postId(), User\ItemGroup::REACTION);
    }

    private function reaction(): User\Item
    {
        return $this->user($this->userFactory)->findItem($this->postId(), User\ItemGroup::REACTION);
    }
}

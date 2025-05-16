<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Blocks\Bookmark;

use Widoz\Wp\Konomi\User;
use Widoz\Wp\Konomi\Blocks;

/**
 * @internal
 */
class Context implements Blocks\Context
{
    public static function new(
        User\UserFactory $userFactory,
        Blocks\InstanceId $instanceId
    ): Context {

        return new self($userFactory, $instanceId);
    }

    final private function __construct(
        readonly private User\UserFactory $userFactory,
        readonly private Blocks\InstanceId $instanceId
    ) {
    }

    /**
     * @return array{
     *     isActive: bool
     * }
     */
    public function toArray(): array
    {
        $bookmark = $this->bookmark();

        return [
            'isActive' => $bookmark->isActive(),
        ];
    }

    public function isUserLoggedIn(): bool
    {
        return $this->user()->isLoggedIn();
    }

    public function postType(): string
    {
        return (string) get_post_type($this->postId());
    }

    public function postId(): int
    {
        return (int) get_the_ID();
    }

    public function instanceId(): Blocks\InstanceId
    {
        return $this->instanceId;
    }

    private function bookmark(): User\Item
    {
        return $this->user()->findItem($this->postId(), User\ItemGroup::BOOKMARK);
    }

    private function user(): User\User
    {
        static $user = null;
        $user or $user = $this->userFactory->create();
        return $user;
    }
}

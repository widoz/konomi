<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Blocks\Bookmark;

use Widoz\Wp\Konomi\User;

/**
 * @internal
 */
class Context
{
    private int $instanceId = 0;

    public static function new(User\UserFactory $userFactory): Context
    {
        return new self($userFactory);
    }

    final private function __construct(
        readonly private User\UserFactory $userFactory
    ) {
    }

    /**
     * @return array{
     *     id: int,
     *     type: string,
     *     isUserLoggedIn: bool,
     *     isActive: bool
     * }
     */
    public function toArray(): array
    {
        $bookmark = $this->bookmark();

        return [
            'id' => $this->postId(),
            'type' => $this->postType(),
            'isUserLoggedIn' => $this->isUserLoggedIn(),
            'isActive' => $bookmark->isActive(),
            'error' => [
                'code' => '',
                'message' => '',
            ],
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

    public function instanceId(): int
    {
        return ++$this->instanceId;
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

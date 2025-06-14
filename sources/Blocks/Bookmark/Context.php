<?php

declare(strict_types=1);

namespace SpaghettiDojo\Konomi\Blocks\Bookmark;

use SpaghettiDojo\Konomi\User;
use SpaghettiDojo\Konomi\Blocks;

/**
 * @internal
 */
class Context implements Blocks\Context
{
    use Blocks\PostContextTrait;
    use Blocks\UserContextTrait;

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

    public function instanceId(): Blocks\InstanceId
    {
        return $this->instanceId;
    }

    private function bookmark(): User\Item
    {
        return $this->user($this->userFactory)->findItem($this->postId(), User\ItemGroup::BOOKMARK);
    }
}

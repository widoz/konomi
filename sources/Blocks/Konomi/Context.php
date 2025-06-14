<?php

declare(strict_types=1);

namespace SpaghettiDojo\Konomi\Blocks\Konomi;

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
     *     id: int,
     *     type: string,
     *     isUserLoggedIn: bool,
     *     error: array{ code: string, message: string }
     * }
     */
    public function toArray(): array
    {
        return [
            'id' => $this->postId(),
            'type' => $this->postType(),
            'isUserLoggedIn' => $this->isUserLoggedIn(),
            'error' => [
                'code' => '',
                'message' => '',
            ],
        ];
    }

    public function instanceId(): Blocks\InstanceId
    {
        return $this->instanceId;
    }

    private function isUserLoggedIn(): bool
    {
        return $this->user($this->userFactory)->isLoggedIn();
    }
}

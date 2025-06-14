<?php

declare(strict_types=1);

namespace SpaghettiDojo\Konomi\Blocks\Rest;

use SpaghettiDojo\Konomi\Rest\Controller;
use SpaghettiDojo\Konomi\User;

/**
 * @internal
 */
class AddControllerFactory
{
    public static function new(
        User\UserFactory $userFactory,
        User\ItemFactory $itemFactory,
    ): AddControllerFactory {

        return new self($userFactory, $itemFactory);
    }

    final private function __construct(
        private readonly User\UserFactory $userFactory,
        private readonly User\ItemFactory $itemFactory
    ) {
    }

    public function create(User\ItemGroup $itemGroup, AddResponse $addResponse): Controller
    {
        return AddController::new(
            $this->userFactory,
            $this->itemFactory,
            $itemGroup,
            $addResponse,
        );
    }
}

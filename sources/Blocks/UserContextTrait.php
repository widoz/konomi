<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Blocks;

use Widoz\Wp\Konomi\User;

trait UserContextTrait
{
    private function user(User\UserFactory $userFactory): User\User
    {
        static $user = null;
        $user or $user = $userFactory->create();
        return $user;
    }
}

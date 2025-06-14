<?php

declare(strict_types=1);

namespace SpaghettiDojo\Konomi\Blocks;

use SpaghettiDojo\Konomi\User;

trait UserContextTrait
{
    private function user(User\UserFactory $userFactory): User\User
    {
        static $user = null;
        $user or $user = $userFactory->create();
        return $user;
    }
}

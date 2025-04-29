<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\User;

/**
 * @api
 */
class UserFactory
{
    public static function new(CurrentUser $currentUser): UserFactory
    {
        return new self($currentUser);
    }

    final private function __construct(readonly private CurrentUser $currentUser)
    {
    }

    public function create(): User
    {
        return $this->currentUser;
    }
}

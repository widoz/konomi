<?php
declare(strict_types=1);

namespace Widoz\Wp\Konomi\User;

class User
{
    public static function new(): User
    {
        return new self();
    }

    final private function __construct() {}

    public function isLoggedIn(): bool
    {
        return is_user_logged_in();
    }

    public function id(): ?int
    {
       return get_current_user_id() ?: null;
    }
}

<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\User\Likes\Meta;

use Widoz\Wp\Konomi\User;

class Read implements User\Meta\Read
{
    public static function new(): Read
    {
        return new self();
    }

    final private function __construct()
    {
    }

    public function read(User\User $user): array
    {
        $likes = get_user_meta($user->id(), '_likes', true);
        return is_array($likes) ? $likes : [];
    }
}

<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\User;

use function Widoz\Wp\Konomi\package;

function currentUser(): User
{
    return package()->container()->get('konomi.user.current');
}

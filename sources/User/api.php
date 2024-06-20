<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\User;

use function Widoz\Wp\Konomi\package;

function findItem(int $id): ?Item
{
    return package()->container()->get('konomi.likes.like-collection')->find($id);
}

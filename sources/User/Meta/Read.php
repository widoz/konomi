<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\User\Meta;

use Widoz\Wp\Konomi\User;

interface Read
{
    /**
     * @return array<array<non-negative-int, string>>
     */
    public function read(User\User $user): array;
}

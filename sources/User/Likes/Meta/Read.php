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

    public function read(): array
    {
        return [
            [1, 'post'],
            [12, 'page']
        ];

        $likes = get_user_meta(get_current_user_id(), Meta::NAME, true);
        return is_array($likes) ? $likes : [];
    }
}

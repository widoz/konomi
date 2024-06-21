<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Icons;

use function Widoz\Wp\Konomi\package;

function icon(): Render
{
    return package()->container()->get('konomi.icons.render');
}

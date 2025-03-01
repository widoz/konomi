<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Icons;

use function Widoz\Wp\Konomi\package;

/**
 * @api
 */
function icon(): Render
{
    return package()->container()->get('konomi.icon');
}

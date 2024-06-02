<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Functions;

use Widoz\Wp\Konomi\Configuration;

function configuration(): Configuration\Configuration
{
    return package()->container()->get(Configuration\Configuration::class);
}

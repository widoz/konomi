<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Icons;

use function Widoz\Wp\Konomi\package;

function renderIcon(string $name): string
{
    return package()->container()->get(Render::class)->render($name);
}

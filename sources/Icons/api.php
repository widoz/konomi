<?php

declare(strict_types=1);

namespace SpaghettiDojo\Konomi\Icons;

use function SpaghettiDojo\Konomi\package;

function icon(): Render
{
    return package()->container()->get(Render::class);
}

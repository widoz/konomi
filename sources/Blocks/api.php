<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Blocks;

use function Widoz\Wp\Konomi\package;

function context(): Like\Context
{
    return package()->container()->get(Like\Context::class);
}

function renderer(): TemplateRender
{
    return package()->container()->get(TemplateRender::class);
}

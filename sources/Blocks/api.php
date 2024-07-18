<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Blocks;

use function Widoz\Wp\Konomi\package;

function context(): LikeContext
{
    return package()->container()->get('konomi.blocks.like-context');
}

function renderer(): TemplateRender
{
    return package()->container()->get('konomi.blocks.template-render');
}

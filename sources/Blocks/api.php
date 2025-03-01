<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Blocks;

use Widoz\Wp\Konomi\Blocks\Like\Context;

use function Widoz\Wp\Konomi\package;

function context(): Context
{
    return package()->container()->get('konomi.blocks.like.context');
}

function renderer(): TemplateRender
{
    return package()->container()->get('konomi.blocks.template-render');
}

<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Blocks;

use Widoz\Wp\Konomi\Blocks\Like\LikeContext;
use function Widoz\Wp\Konomi\package;

/**
 * @api
 */
function context(): LikeContext
{
    return package()->container()->get('konomi.blocks.like-context');
}

/**
 * @api
 */
function renderer(): TemplateRender
{
    return package()->container()->get('konomi.blocks.template-render');
}

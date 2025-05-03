<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Blocks;

use function Widoz\Wp\Konomi\package;

// TODO Make it internal for Like
function likeContext(): Like\Context
{
    return package()->container()->get(Like\Context::class);
}

// TODO Make it internal for Bookmark
function bookmarkContext(): Bookmark\Context
{
    return package()->container()->get(Bookmark\Context::class);
}

function renderer(): TemplateRender
{
    return package()->container()->get(TemplateRender::class);
}

function style(): Style
{
    return Style::new();
}

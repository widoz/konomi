<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Blocks;

use function Widoz\Wp\Konomi\package;

function context(string $contextName): Context
{
    $service = package()->container()->get($contextName);
    if (!$service instanceof Context) {
        throw new \InvalidArgumentException(
            // phpcs:ignore WordPress.Security.EscapeOutput.ExceptionNotEscaped
            "Service '{$contextName}' is not a valid context."
        );
    }

    return $service;
}

function renderer(): TemplateRender
{
    return package()->container()->get(TemplateRender::class);
}

function style(): Style
{
    return Style::new();
}

<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Icons;

use function Widoz\Wp\Konomi\package;

function icon(): Render
{
    return package()->container()->get('konomi.icon');
}

function ksesIcon(string $markup): string
{
    return wp_kses(
        $markup,
        [
            'svg' => [
                'width' => true,
                'height' => true,
                'fill' => true,
                'class' => true,
                'viewBox' => true,
                'version' => true,
                'xmlns' => true,
                'xmlns:svg' => true,
            ],
            'path' => [
                'd' => true,
            ],
        ]
    );
}

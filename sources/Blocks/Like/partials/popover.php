<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Blocks\Like;

$data = (array) ($data ?? null);

$anchor = (string) ($data['anchor'] ?? null);
?>

<span
    popover="manual"
    class="konomi-like-response-message"
    style="position-anchor: <?= esc_attr($anchor) ?>"
>
</span>

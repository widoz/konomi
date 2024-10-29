<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Blocks\Like;

$anchor = (string) ($data['anchor'] ?? null);
$defaultMessage = (string) ($data['defaultMessage'] ?? null);
?>

<span
    popover="manual"
    class="konomi-like-response-message"
    style="position-anchor: <?= esc_attr($anchor) ?>"
>
    <?= esc_html($defaultMessage) ?>
</span>

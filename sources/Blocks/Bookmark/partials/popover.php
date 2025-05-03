<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Blocks\Bookmark;

$data = (array) ($data ?? null);

$anchor = (string) ($data['anchor'] ?? null);
?>

<span
    popover="manual"
    class="konomi-bookmark-response-message"
    style="position-anchor: <?= esc_attr($anchor) ?>"
    data-wp-text="context.error.message"
    data-wp-run--maybe-render-response-error="callbacks.maybeRenderResponseError"
>
</span>

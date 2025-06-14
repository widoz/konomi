<?php

declare(strict_types=1);

namespace SpaghettiDojo\Konomi\Blocks;

$data = (array) ($data ?? null);

$anchor = (string) ($data['anchor'] ?? null);
?>

<span
    popover="manual"
    class="konomi-response-message"
    style="position-anchor: <?= esc_attr($anchor) ?>"
    data-wp-text="context.error.message"
    data-wp-run--maybe-render-response-error="callbacks.maybeRenderResponseError"
>
</span>

<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Blocks;

use Widoz\Wp\Konomi\Icons;

$data = (array) ($data ?? null);

$anchor = (string) ($data['anchor'] ?? null);
$label = (string) ($data['label'] ?? null);
$iconName = (string) ($data['icon'] ?? null);
?>

<button
    <?php
    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    echo get_block_wrapper_attributes() ?>
    data-wp-class--is-active="context.isActive"
    data-wp-on-async--click="actions.toggleStatus"
    data-wp-run--maybe-show-login-modal="callbacks.toggleLoginModal"
    style="anchor-name: <?= esc_attr($anchor) ?>"
>
    <?=
    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    Icons\icon()->render($iconName) ?>

    <?php if ($label) : ?>
        <span class="konomi-label" data-wp-text="context.label">
            <?= esc_html($label) ?>
        </span>
    <?php endif ?>

    <span class="konomi-count" data-wp-text="context.count"></span>
</button>

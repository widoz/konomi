<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Blocks\Bookmark;

use Widoz\Wp\Konomi\Icons;

$data = (array) ($data ?? null);

$anchor = (string) ($data['anchor'] ?? null);
$label = (string) ($data['label'] ?? null);
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
    Icons\icon()->render('bookmark') ?>

    <?php if ($label) : ?>
        <span class="konomi-bookmark-label" data-wp-text="context.label">
            <?= esc_html($label) ?>
        </span>
    <?php endif ?>
</button>

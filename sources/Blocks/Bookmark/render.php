<?php

declare(strict_types=1);

namespace SpaghettiDojo\Konomi\Blocks\Bookmark;

use SpaghettiDojo\Konomi\Blocks;

$attributes = (array) ($attributes ?? null);

$inactiveColor = (string) ($attributes['inactiveColor'] ?? null);
$activeColor = (string) ($attributes['activeColor'] ?? null);

$renderer = Blocks\renderer();
$context = Blocks\context(Context::class);

$uuid = $context->instanceId()->current();
$anchor = "--konomi-{$uuid}";

$style = (string) Blocks\style()->add(
    Blocks\CustomProperty::new('--konomi-color--inactive', $inactiveColor, 'sanitize_hex_color'),
    Blocks\CustomProperty::new('--konomi-color--active', $activeColor, 'sanitize_hex_color'),
);
?>

<div
    data-wp-interactive="konomiBookmark"
    class="konomi-bookmark"
    style="<?= esc_attr($style) ?>"
    <?php
    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    echo wp_interactivity_data_wp_context($context->toArray()) ?>
>
    <?=
    /*
     * phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
     */
    $renderer->render('partials/button', [
        'anchor' => $anchor,
        'label' => esc_html__('Bookmark this post', 'konomi'),
        'icon' => 'bookmark',
    ]) ?>
</div>

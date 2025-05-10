<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Blocks\Bookmark;

use Widoz\Wp\Konomi\Blocks;

$attributes = (array) ($attributes ?? null);

$inactiveColor = (string) ($attributes['inactiveColor'] ?? null);
$activeColor = (string) ($attributes['activeColor'] ?? null);

$renderer = Blocks\renderer();
$context = Blocks\context(Context::class);
$generatedContext = $context->toArray();
$uuid = $context->instanceId();
$anchor = "--konomi-bookmark-{$uuid}";

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
    echo wp_interactivity_data_wp_context($generatedContext) ?>
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

    <?=
    $renderer->render('partials/popover', [
        'anchor' => $anchor,
    ]) ?>

    <?=
    $renderer->render('partials/dialog', [
        'loginPageUrl' => wp_login_url(add_query_arg([])),
        'loginPageLabel' => esc_html__('Login', 'konomi'),
        'title' => esc_html__('Sign in to bookmark', 'konomi'),
        'message' => esc_html__('You need to be signed in to save your bookmarks.', 'konomi'),
        'closeLabel' => esc_html__('Close', 'konomi'),
    ])
    // phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
    ?>
</div>

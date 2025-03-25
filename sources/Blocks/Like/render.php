<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Blocks\Like;

use Widoz\Wp\Konomi\Blocks;

$attributes = (array) ($attributes ?? null);

$inactiveColor = (string) ($attributes['inactiveColor'] ?? null);
$activeColor = (string) ($attributes['activeColor'] ?? null);

$renderer = Blocks\renderer();
$context = Blocks\context();
$generatedContext = $context->toArray();
$uuid = $context->instanceId();
$id = $context->postId();
$anchor = "--konomi-like-{$uuid}";

$style = (string) Blocks\style()->add(
    Blocks\CustomProperty::new('--konomi-color--inactive', $inactiveColor, 'sanitize_hex_color'),
    Blocks\CustomProperty::new('--konomi-color--active', $activeColor, 'sanitize_hex_color'),
);
?>

<div
    data-wp-interactive="konomi"
    class="konomi-like"
    style="<?= esc_attr($style) ?>"
    <?php
    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    echo wp_interactivity_data_wp_context($generatedContext) ?>
>
    <?=
    /*
     * phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
     */
    $renderer->render('Like/partials/button', [
        'anchor' => $anchor,
        'count' => $context->count(),
        'label' => esc_html__('Save this post', 'konomi'),
    ]) ?>

    <?=
    $renderer->render('Like/partials/popover', [
        'anchor' => $anchor,
        'defaultMessage' => esc_html__('Unknown error, please try again later!', 'konomi'),
    ]) ?>

    <?=
    $renderer->render('Like/partials/dialog', [
        'postId' => $id,
        'loginPageUrl' => wp_login_url(add_query_arg([])),
        'loginPageLabel' => esc_html__('Login', 'konomi'),
        'title' => esc_html__('Sign in to like', 'konomi'),
        'message' => esc_html__('You need to be signed in to save your likes.', 'konomi'),
        'closeLabel' => esc_html__('Close', 'konomi'),
    ])
    // phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
    ?>
</div>

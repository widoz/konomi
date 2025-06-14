<?php

declare(strict_types=1);

namespace SpaghettiDojo\Konomi\Blocks\Konomi;

use SpaghettiDojo\Konomi\Blocks;

$content = (string) ($content ?? null);

$renderer = Blocks\renderer();
$context = Blocks\context(Context::class);

$uuid = $context->instanceId()->current();
$anchor = "--konomi-{$uuid}";

$context->instanceId()->reset();
?>

<div
    data-wp-interactive="konomi"
    class="konomi"
    <?php
    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    echo wp_interactivity_data_wp_context($context->toArray()) ?>
>
    <?=
    wp_kses($content, [
        'div' => [
            'class' => true,
            'data-wp-interactive' => true,
            'data-wp-context' => true,
            'style' => true,
        ],
        'button' => [
            'class' => true,
            'data-wp-class--is-active' => true,
            'data-wp-on-async--click' => true,
            'data-wp-run--maybe-show-login-modal' => true,
            'style' => true,
            'type' => true,
        ],
        'svg' => [
            'width' => true,
            'height' => true,
            'fill' => true,
            'class' => true,
            'version' => true,
            'xmlns' => true,
            'xmlns:svg' => true,
        ],
        'path' => [
            'd' => true,
        ],
        'span' => [
            'class' => true,
            'data-wp-text' => true,
            'data-wp-run--maybe-render-response-error' => true,
            'popover' => true,
            'style' => true,
        ],
        'dialog' => [
            'class' => true,
        ],
        'h2' => [],
        'p' => [],
        'a' => [
            'href' => true,
            'class' => true,
            'target' => true,
            'rel' => true,
        ],
    ]); ?>

    <?=
    /*
     * phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
     */
    $renderer->render('Konomi/partials/popover', [
        'anchor' => $anchor,
    ]) ?>

    <?=
    $renderer->render('Konomi/partials/dialog', [
        'loginPageUrl' => wp_login_url(add_query_arg([])),
        'loginPageLabel' => esc_html__('Login', 'konomi'),
        'title' => esc_html__('Sign in to bookmark', 'konomi'),
        'message' => esc_html__('You need to be signed in to save your bookmarks.', 'konomi'),
        'closeLabel' => esc_html__('Close', 'konomi'),
    ])
    // phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
    ?>
</div>

<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Blocks\Konomi;

use Widoz\Wp\Konomi\Blocks;

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
     // TODO Double check if it's possible to restrict it somehow.
     // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    $content ?>

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

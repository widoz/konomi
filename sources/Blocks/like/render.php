<?php

declare(strict_types=1);

use Widoz\Wp\Konomi\Blocks;
use Widoz\Wp\Konomi\Icons;

$context = Blocks\context();
$generatedContext = $context->generate();
$uuid = $context->instanceId();
$anchor = "--konomi-like-{$uuid}";
?>

<div
    data-wp-interactive="konomi"
    class="konomi-like"
    <?php
    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    echo wp_interactivity_data_wp_context($generatedContext) ?>
>
    <button
        <?php
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo get_block_wrapper_attributes() ?>
        data-wp-class--is-active="context.isActive"
        data-wp-on--click="actions.toggleStatus"
        data-wp-run--maybe-show-errors="callbacks.maybeShowErrorPopup"
        data-wp-run--maybe-show-login-modal="callbacks.toggleLoginModal"
        style="anchor-name: <?= esc_attr($anchor) ?>"
    >
        <?=
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        Icons\ksesIcon(Icons\icon()->render('heart')) ?>

        <span class="screen-reader-text">
            <?= esc_html__('Save this post', 'konomi') ?>
        </span>

        <span class="konomi-like-count" data-wp-text="context.count">
            <?= esc_html($generatedContext['count']) ?>
        </span>
    </button>

    <span
        popover="manual"
        class="konomi-like-response-message"
        style="position-anchor: <?= esc_attr($anchor) ?>"
    >
        <?= esc_html__('Unknown error, please try again later!', 'konomi') ?>
    </span>

    <dialog class="konomi-login-modal">
        <h2><?= esc_html__('Sign in to like', 'konomi') ?></h2>
        <p><?= esc_html__('You need to be signed in to save your likes.', 'konomi') ?></p>
        <a href="<?= esc_url(wp_login_url(add_query_arg(['page_id' => 6]))) ?>">
            <?= esc_html__('Login', 'konomi') ?>
        </a>
        <button
            class="konomi-login-modal-closer"
            data-wp-on--click="actions.closeLoginModal"
        >
            <?=
            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            Icons\ksesIcon(Icons\icon()->render('close')) ?>
            <span class="screen-reader-text">
                <?= esc_html__('Close', 'konomi') ?>
            </span>
        </button>
    </dialog>
</div>

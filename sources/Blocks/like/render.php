<?php

declare(strict_types=1);

use Widoz\Wp\Konomi\Blocks;
use Widoz\Wp\Konomi\Icons;
use Widoz\Wp\Konomi\User;

if (!User\user()->isLoggedIn()) {
    return;
}

$context = Blocks\context();
$generatedContext = $context->generate();
$uuid = $context->instanceId();
$anchor = "--konomi-like-{$uuid}";
?>

<div class="konomi-like">
    <span
        popover="manual"
        class="konomi-like-response-message"
        style="position-anchor: <?= esc_attr($anchor) ?>"
    >
        <?= esc_html__('Unknown error, please try again later!', 'konomi') ?>
    </span>
    <button
        <?php
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo get_block_wrapper_attributes() ?>
        <?php
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo wp_interactivity_data_wp_context($generatedContext) ?>
        data-wp-interactive="konomi"
        data-wp-run--maybe-show-errors="callbacks.maybeShowErrorPopup"
        data-wp-run--update-like-count="callbacks.updateLikeCount"
        data-wp-on--click="actions.toggleStatus"
        data-wp-on--click--persist="actions.updateUserPreferences"
        data-wp-class--is-active="context.isActive"
        style="anchor-name: <?= esc_attr($anchor) ?>"
    >
        <?=
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        Icons\ksesIcon(Icons\icon()->render('heart')) ?>

        <span class="screen-reader-text">
            <?= esc_html__('Save this post', 'konomi') ?>
        </span>

        <span class="konomi-like-count">
            <?= esc_html($generatedContext['count']) ?>
        </span>
    </button>
</div>

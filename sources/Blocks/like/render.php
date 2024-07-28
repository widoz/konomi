<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Blocks\Like;

use Widoz\Wp\Konomi\Blocks;

$inactiveColor = (string) ($attributes['inactiveColor'] ?? null);
$activeColor = (string) ($attributes['activeColor'] ?? null);

$renderer = Blocks\renderer();
$context = Blocks\context();
$generatedContext = $context->toArray();
$uuid = $context->instanceId();
$id = $context->postId();
$anchor = "--konomi-like-{$uuid}";

$style = Blocks\Style::new()->add(
    Blocks\CustomProperty::new('--konomi-color--inactive', $inactiveColor),
    Blocks\CustomProperty::new('--konomi-color--active', $activeColor),
);
?>

<div
    data-wp-interactive="konomi"
    class="konomi-like"
    style="<?= $style ?>"
    <?php
    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    echo wp_interactivity_data_wp_context($generatedContext) ?>
>
    <?= $renderer->render('like/partials/button', [
        'anchor' => $anchor,
        'count' => $context->count(),
        'label' => __('Save this post', 'konomi'),
    ]) ?>

    <?= $renderer->render('like/partials/popover', [
        'anchor' => $anchor,
        'defaultMessage' => __('Unknown error, please try again later!', 'konomi'),
    ]) ?>

    <?= $renderer->render('like/partials/dialog', [
        'postId' => $id,
        'loginPageUrl' => wp_login_url(add_query_arg([])),
        'loginPageLabel' => __('Login', 'konomi'),
        'title' => __('Sign in to like', 'konomi'),
        'message' => __('You need to be signed in to save your likes.', 'konomi'),
        'closeLabel' => __('Close', 'konomi'),
    ]) ?>
</div>

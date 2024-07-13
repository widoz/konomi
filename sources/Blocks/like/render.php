<?php

declare(strict_types=1);

use Widoz\Wp\Konomi\Blocks;
use Widoz\Wp\Konomi\Icons;
use Widoz\Wp\Konomi\User;

if (!User\user()->isLoggedIn()) {
    return;
}

$context = Blocks\context();
$uuid = $context->instanceId();
?>

<div class="konomi-like">
    <span
        style="position-anchor: --konomi-like-<?= $uuid ?>;"
        popover="manual"
        class="konomi-like-response-message"
    ></span>
    <button
        <?= get_block_wrapper_attributes() ?>
        <?= wp_interactivity_data_wp_context($context->generate()) ?>
        data-wp-interactive="konomi"
        data-wp-run="callbacks.maybeShowErrorPopup"
        data-wp-on--click="actions.toggleStatus"
        data-wp-class--is-active="context.isActive"
        style="anchor-name: --konomi-like-<?= $uuid ?>;"
    >
        <?= Icons\icon()->render('heart') ?>
    </button>
</div>

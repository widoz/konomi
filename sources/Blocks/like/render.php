<?php

declare(strict_types=1);

use Widoz\Wp\Konomi\Blocks;
use Widoz\Wp\Konomi\Icons;
use Widoz\Wp\Konomi\User;

if (!User\user()->isLoggedIn()) {
    return;
}
?>

<button
    <?= get_block_wrapper_attributes() ?>
    <?= wp_interactivity_data_wp_context(Blocks\context()->generate()) ?>
    data-wp-interactive="konomi"
    data-wp-on--click="actions.toggleStatus"
    data-wp-class--is-active="context.isActive"
>
    <?= Icons\icon()->render('heart') ?>
</button>

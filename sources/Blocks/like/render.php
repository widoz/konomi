<?php

declare(strict_types=1);

use Widoz\Wp\Konomi\Blocks;
use Widoz\Wp\Konomi\Icons;
use Widoz\Wp\Konomi\User;

if (!User\currentUser()->isLoggedIn()) {
    return;
}
?>

<button
    <?= get_block_wrapper_attributes() ?>
    data-wp-interactive="konomi"
    data-wp-watch="callbacks.updateUserPreferences"
    data-wp-context="<?= esc_attr(Blocks\context()->serialize()) ?>"
    data-wp-on--click="actions.toggleStatus"
    data-wp-class--is-active="context.isActive"
>
    <?= Icons\icon()->render('heart') ?>
</button>

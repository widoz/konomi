<?php

declare(strict_types=1);

use Widoz\Wp\Konomi\Icons;
use Widoz\Wp\Konomi\User;

$id = get_the_ID();
$type = get_post_type($id);

$context = wp_json_encode([
    'entityId' => $id,
    'entityType' => $type,
    'isActive' => !!User\findItem($id),
]);
?>

<button
    <?= get_block_wrapper_attributes() ?>
    data-wp-interactive="konomi"
    data-wp-context="<?= esc_attr($context) ?>"
    data-wp-on--click="actions.toggleStatus"
    data-wp-class--is-active="context.isActive"
>
    <?= Icons\renderIcon('heart') ?>
</button>

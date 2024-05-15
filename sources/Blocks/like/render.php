<?php

//$arguments = sanitize_block_arguments($arguments);
$arguments = wp_parse_args($args, [
    'title' => '',
]);
?>

<div <?= get_block_wrapper_attributes(); ?>>
    <?php if (!empty($arguments['title'])) : ?>
        <p><?= $arguments['title']; ?></p>
    <?php endif; ?>
</div>

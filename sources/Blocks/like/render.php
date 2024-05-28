<?php

declare(strict_types=1);

$konomi = get_option('konomi');
$imgUrl = (string)($konomi['iconsPathUrl'] ?? '');
?>

<button <?= get_block_wrapper_attributes() ?>>
    <img src="<?= esc_url("{$imgUrl}/suit-heart.svg") ?>" alt="Konomi Icon" />
</button>

<?php

declare(strict_types=1);

use Widoz\Wp\Konomi;

$configuration = Konomi\Functions\configuration();
$imgUrl = $configuration->iconsPathUrl();
?>

<button <?= get_block_wrapper_attributes() ?>>
    <img src="<?= esc_url("{$imgUrl}/suit-heart.svg") ?>" alt="Konomi Icon" />
</button>

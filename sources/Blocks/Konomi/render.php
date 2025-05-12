<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Blocks\Like;

$content = (string) ($content ?? null);
?>

<div
    data-wp-interactive="konomi"
    class="konomi-konomi"
>
    <?= $content ?>
</div>

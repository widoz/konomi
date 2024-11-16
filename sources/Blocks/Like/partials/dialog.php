<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Blocks\Like;

use Widoz\Wp\Konomi\Icons;

$data = (array) ($data ?? null);

$loginPageUrl = (string) ($data['loginPageUrl'] ?? null);
$loginPageLabel = (string) ($data['loginPageLabel'] ?? null);
$title = (string) ($data['title'] ?? null);
$message = (string) ($data['message'] ?? null);
$closeLabel = (string) ($data['closerLabel'] ?? null);
?>

<dialog class="konomi-login-modal">
    <h2><?= esc_html($title) ?></h2>

    <p><?= esc_html($message) ?></p>

    <a href="<?= esc_url($loginPageUrl) ?>">
        <?= esc_html($loginPageLabel) ?>
    </a>

    <button
        class="konomi-login-modal-closer"
        data-wp-on--click="actions.closeLoginModal"
    >
        <?=
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        Icons\icon()->render('close') ?>
        <span class="screen-reader-text">
            <?= esc_html($closeLabel) ?>
        </span>
    </button>
</dialog>


<?php

declare(strict_types=1);

require_once __DIR__ . '/functions.php';

function setUpHooks(): void
{
    require_once wordPressDirPath() . '/wp-includes/plugin.php';
}

function setUpWpError(): void
{
    require_once wordPressDirPath() . '/wp-includes/class-wp-error.php';
}

function setUpWpRest(): void
{
    require_once wordPressDirPath() . '/wp-includes/class-wp-http-response.php';
    require_once wordPressDirPath() . '/wp-includes/rest-api/class-wp-rest-request.php';
    require_once wordPressDirPath() . '/wp-includes/rest-api/class-wp-rest-response.php';
}

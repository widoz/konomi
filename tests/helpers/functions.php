<?php

declare(strict_types=1);

function projectRootDirectory(): string
{
    return dirname(__DIR__, 2);
}

function stubsDirectory(): string
{
    return dirname(__DIR__) . '/stubs';
}

function fixturesDirectory(): string
{
    return dirname(__DIR__) . '/fixtures/php';
}

<?php

declare(strict_types=1);

namespace Pest;

uses()
    ->beforeAll(static function (): void {
        \Brain\Monkey\setUp();
    })
    ->afterAll(static function (): void {
        \Brain\Monkey\tearDown();
    })
    ->in('unit', 'integration');

uses()
    ->beforeAll(static function (): void {
        require_once __DIR__ . '/stubs/php/wp-assets.php';
        require_once __DIR__ . '/stubs/php/wp-hooks.php';
    })
    ->in('integration');

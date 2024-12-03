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

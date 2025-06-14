<?php

declare(strict_types=1);

namespace SpaghettiDojo\Konomi\Tests\Unit\Blocks;

use SpaghettiDojo\Konomi\Blocks\CustomProperty;

describe('isValid', function (): void {
    it('Instantiate a valid key value pair', function (): void {
        $customProperty = CustomProperty::new(
            'key',
            'value',
            static fn (mixed $value): mixed => $value
        );
        expect($customProperty->isValid())->toBeTrue();
    });
});

describe('__toString', function (): void {
    it('Sanitize Value', function (): void {
        $customProperty = CustomProperty::new('key', 'value', static fn (): string => 'sanitized');
        expect($customProperty->__toString())->toBe('key:sanitized;');
    });
});

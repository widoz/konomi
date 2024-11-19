<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Tests\Unit\Blocks;

use Widoz\Wp\Konomi\Blocks\CustomProperty;

describe('Custom Property', function (): void {
    it('Instantiate a valid key value pair', function (): void {
        $customProperty = CustomProperty::new(
            'key',
            'value',
            static fn (mixed $value): mixed => $value
        );
        expect($customProperty->isValid())->toBeTrue();
    });

    it('Instantiate an invalid key value pair', function (): void {
        $customProperty = CustomProperty::new(
            'key',
            null,
            static fn (mixed $value): mixed => $value
        );
        expect($customProperty->isValid())->toBeFalse();
    });

    it('Sanitize Value', function (): void {
        $customProperty = CustomProperty::new('key', 'value', static fn (): string => 'sanitized');
        expect($customProperty->__toString())->toBe('key:sanitized;');
    });
});

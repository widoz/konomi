<?php

declare(strict_types=1);

namespace SpaghettiDojo\Konomi\Tests\Unit\Blocks;

use SpaghettiDojo\Konomi\Blocks\Style;
use SpaghettiDojo\Konomi\Blocks\CustomProperty;

describe('__toString', function (): void {
    it('reduce given properties to css string', function (): void {
        $sanitizer = static fn (mixed $value): mixed => $value;
        $style = Style::new()
            ->add(
                CustomProperty::new('color', 'red', $sanitizer),
                CustomProperty::new('font-size', '16px', $sanitizer),
                CustomProperty::new('font-weight', 'bold', $sanitizer),
            );

        expect($style->__toString())->toBe('color:red;font-size:16px;font-weight:bold;');
    });
});

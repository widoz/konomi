<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Tests\Unit\Blocks;

use Widoz\Wp\Konomi\Blocks\Style;
use Widoz\Wp\Konomi\Blocks\CustomProperty;

describe('Style Test', function (): void {
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

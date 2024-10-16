<?php

declare(strict_types=1);

use Widoz\Wp\Konomi\Blocks\Style;
use Widoz\Wp\Konomi\Blocks\CustomProperty;

describe('Style Test', function (): void {
    it('reduce given properties to css string', function (): void {
        $style = Style::new()
            ->add(
                CustomProperty::new('color', 'red'),
                CustomProperty::new('font-size', '16px'),
                CustomProperty::new('font-weight', 'bold')
            );

        expect($style->__toString())->toBe('color:red;font-size:16px;font-weight:bold;');
    });
});

<?php

declare(strict_types=1);

use Widoz\Wp\Konomi\Blocks\CustomProperty;

describe('Custom Property', function () {
    it('Instantiate a valid key value pair', function () {
        $customProperty = CustomProperty::new('key', 'value');
        expect($customProperty->isValid())->toBeTrue();
    });

    it('Instantiate an invalid key value pair', function () {
        $customProperty = CustomProperty::new('key', null);
        expect($customProperty->isValid())->toBeFalse();
    });
});

<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Tests\Unit\Configuration;

describe('serialize', function (): void {
    it('Serialize the given configuration', function (): void {
        /** @var \Inpsyde\Modularity\Properties\Properties&\Mockery\MockInterface */
        $properties = \Mockery::mock('\Inpsyde\Modularity\Properties\Properties');
        $properties->shouldReceive('baseUrl')->andReturn('http://example.com');
        $properties->shouldReceive('basePath')->andReturn('/var/www/html');
        $properties->shouldReceive('isDebug')->andReturn(true);

        $expected = '{"iconsUrl":"http:\/\/example.com\/\/path\/to\/icons","iconsPath":"\/var\/www\/html\/\/path\/to\/icons","isDebugMode":true}';
        $configuration = \Widoz\Wp\Konomi\Configuration\Configuration::new($properties, '/path/to/icons');
        expect($configuration->serialize())->toBe($expected);
    });
});

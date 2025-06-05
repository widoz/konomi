<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Tests\Unit\Blocks\Rest;

use Widoz\Wp\Konomi\Blocks;

describe('toArray', function (): void {
    it('should return the correct schema', function (): void {
        $schema = Blocks\Rest\AddSchema::new('title');

        expect($schema->toArray())->toBe([
            'title' => 'title',
            'type' => 'object',
            'properties' => [
                'id' => [
                    'required' => true,
                    'type' => 'integer',
                ],
                'type' => [
                    'required' => true,
                    'type' => 'string',
                ],
                'isActive' => [
                    'required' => true,
                    'type' => 'boolean',
                ],
            ],
        ]);
    });
});

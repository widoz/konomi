<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Tests\Unit\Blocks\Like\Rest;

use Widoz\Wp\Konomi\Blocks;

describe('Add Schema', function (): void {
    describe('toArray', function(): void {
        it('should return the correct schema', function (): void {
            $schema = Blocks\Like\Rest\AddSchema::new();

            expect($schema->toArray())->toBe([
                'title' => '_like',
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
});

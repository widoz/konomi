<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Blocks\Like\Rest;

use Widoz\Wp\Konomi\Rest;

/**
 * @internal
 */
class AddSchema implements Rest\Schema
{
    public static function new(): AddSchema
    {
        return new self();
    }

    final private function __construct()
    {
    }

    public function toArray(): array
    {
        return [
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
        ];
    }
}

<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Blocks\Bookmark\Rest;

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

    /**
     * @return array{
     *     title: '_bookmark',
     *     type: string,
     *     properties: array<string, array{required: bool, type: string}>
     * }
     */
    public function toArray(): array
    {
        return [
            'title' => '_bookmark',
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

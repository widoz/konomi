<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Blocks\Rest;

use Widoz\Wp\Konomi\Rest;

/**
 * @internal
 * @phpstan-type PropertiesSchema = array{
 *      title: string,
 *      type: string,
 *      properties: array<string, array{required: bool, type: string}>
 *  }
 */
class AddSchema implements Rest\Schema
{
    public static function new(string $title): AddSchema
    {
        return new self($title);
    }

    final private function __construct(readonly string $title)
    {
    }

    /**
     * @return PropertiesSchema
     */
    public function toArray(): array
    {
        return [
            'title' => $this->title,
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

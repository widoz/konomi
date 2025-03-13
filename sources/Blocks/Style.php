<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Blocks;

/**
 * @internal
 */
class Style implements \Stringable
{
    /**
     * @var array<CustomProperty<mixed>>
     */
    private array $properties = [];

    public static function new(): Style
    {
        return new self();
    }

    final private function __construct()
    {
    }

    /**
     * @param CustomProperty<mixed> $property
     * @param CustomProperty<mixed> ...$properties
     */
    public function add(CustomProperty $property, CustomProperty ...$properties): self
    {
        array_push($this->properties, $property, ...$properties);
        return $this;
    }

    public function __toString(): string
    {
        return array_reduce(
            $this->properties,
            static fn (string $output, CustomProperty $property) => $property->isValid()
                    ? "{$output}{$property}"
                    : $output,
            ''
        );
    }
}

<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Blocks;

class Style implements \Stringable
{
    public static function new(): Style
    {
        return new self([]);
    }

    final private function __construct(private array $properties)
    {
    }

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
                    ? $output . "{$property->key}:$property->value;"
                    : $output,
            ''
        );
    }
}

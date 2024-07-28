<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Blocks;

class CustomProperty
{
    public static function new(string $key, mixed $value): CustomProperty
    {
        return new self($key, $value);
    }

    final private function __construct(
        readonly public string $key,
        readonly public mixed $value
    ) {
    }

    public function isValid(): bool
    {
        return !!$this->value;
    }
}

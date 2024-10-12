<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Blocks;

/**
 * @internal
 * @template V
 */
class CustomProperty
{
    /**
     * @param V $value
     */
    public static function new(string $key, mixed $value): self
    {
        return new self($key, $value);
    }

    /**
     * @param V $value
     */
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

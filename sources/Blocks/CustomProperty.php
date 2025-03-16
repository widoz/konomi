<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Blocks;

/**
 * @internal
 */
class CustomProperty
{
    /**
     * @var callable $sanitizer
     */
    private $sanitizer;

    public static function new(string $key, string $value, callable $sanitizer): self
    {
        return new self($key, $value, $sanitizer);
    }

    /**
     * @param callable $sanitizer
     */
    final private function __construct(
        readonly public string $key,
        readonly public string $value,
        $sanitizer
    ) {

        $this->sanitizer = $sanitizer;
    }

    public function isValid(): bool
    {
        return !!$this->value;
    }

    public function __toString(): string
    {
        $sanitizer = $this->sanitizer;
        $sanitizedValue = (string) $sanitizer($this->value);
        return "{$this->key}:{$sanitizedValue};";
    }
}

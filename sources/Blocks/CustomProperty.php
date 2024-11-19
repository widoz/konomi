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
     * @var callable $sanitizer
     */
    private $sanitizer;

    /**
     * @template T extends self::V
     * @param T $value
     * @return self<T>
     */
    public static function new(string $key, mixed $value, callable $sanitizer): self
    {
        return new self($key, $value, $sanitizer);
    }

    /**
     * @param V $value
     * @param callable $sanitizer
     */
    final private function __construct(
        readonly public string $key,
        readonly public mixed $value,
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
        $value = (string) $this->value;
        $sanitizedValue = (string) $sanitizer($value);

        return "{$this->key}:{$sanitizedValue};";
    }
}

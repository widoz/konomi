<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\User;

use Widoz\Wp\Konomi\User;

class StorageKey
{
    public static function new(string $base): StorageKey
    {
        assert($base);
        return new self($base);
    }

    final private function __construct(private readonly string $base)
    {
    }

    /**
     * @throws \InvalidArgumentException If ItemGroup is an empty string
     * @throws \UnexpectedValueException If the returning key is an empty string
     */
    public function for(User\ItemGroup $group): string
    {
        $groupValue = $group->value;

        if (empty($groupValue)) {
            throw new \InvalidArgumentException('Group value cannot be empty');
        }

        $key = preg_replace(
            '/[^a-z0-9._]/',
            '',
            $this->base . '.' . $groupValue
        );

        if (empty($key)) {
            throw new \UnexpectedValueException('Storage key cannot be empty after sanitization');
        }

        return $key;
    }
}

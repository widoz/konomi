<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Post;

use Widoz\Wp\Konomi\User;

class StorageKey
{
    public static function new(string $base): StorageKey
    {
        assert(!empty($base));
        return new self($base);
    }

    final private function __construct(private string $base)
    {
    }

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
            throw new \RuntimeException('Storage key cannot be empty after sanitization');
        }

        return $key;
    }
}

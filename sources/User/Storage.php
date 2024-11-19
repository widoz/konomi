<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\User;

/**
 * @internal
 */
class Storage
{
    public static function new(): Storage
    {
        return new self();
    }

    final private function __construct()
    {
    }

    public function read(int $id, string $key): array
    {
        if ($id <= 0 || $key === '') {
            return [];
        }

        /** @var array|false|string */
        $data = get_user_meta($id, $key, true);
        return is_array($data) ? $data : [];
    }

    public function write(int $id, string $key, array $data): bool
    {
        if ($id <= 0 || $key === '') {
            return false;
        }

        return (bool) update_user_meta($id, $key, $data);
    }
}

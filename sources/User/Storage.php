<?php

declare(strict_types=1);

namespace SpaghettiDojo\Konomi\User;

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

    /**
     * @return array<mixed>
     */
    public function read(int $id, string $key): array
    {
        if ($id <= 0 || $key === '') {
            return [];
        }

        $data = get_user_meta($id, $key, true);
        return is_array($data) ? $data : [];
    }

    /**
     * @param array<mixed> $data
     */
    public function write(int $id, string $key, array $data): bool
    {
        if ($id <= 0 || $key === '') {
            return false;
        }

        return (bool) update_user_meta($id, $key, $data);
    }
}

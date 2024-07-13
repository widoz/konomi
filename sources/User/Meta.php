<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\User;

class Meta
{
    public static function new(): Meta
    {
        return new self();
    }

    final private function __construct()
    {
    }

    public function read(int $id, string $key): array
    {
        $data = get_user_meta($id, $key, true);
        return is_array($data) ? $data : [];
    }

    /**
     * @param array{0: int, 1: string} $data
     */
    public function write(int $id, string $key, array $data): bool
    {
        return (bool) update_user_meta($id, $key, $data);
    }
}

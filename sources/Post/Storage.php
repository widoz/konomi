<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Post;

/**
 * @internal
 */
class Storage
{
    private const KEY = '_konomi_likes';

    public static function new(): Storage
    {
        return new self();
    }

    final private function __construct()
    {
    }

    public function read(int $id): array
    {
        if ($id <= 0) {
            return [];
        }

        /** @var array|false|string $data */
        $data = get_post_meta($id, self::KEY, true);
        return is_array($data) ? $data : [];
    }

    public function write(int $id, array $data): bool
    {
        if ($id <= 0) {
            return false;
        }

        return (bool) update_post_meta($id, self::KEY, $data);
    }
}

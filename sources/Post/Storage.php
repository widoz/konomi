<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Post;

/**
 * @internal
 *
 * @psalm-type RawItem = array{0: int<1, max>, 1: string}
 * @psalm-type RawData = array<int<1, max>, array<RawItem>>
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

    /**
     * @return RawData
     */
    public function read(int $id): array
    {
        $data = get_post_meta($id, self::KEY, true);
        return is_array($data) ? $data : [];
    }

    /**
     * @param RawData $data
     */
    public function write(int $id, array $data): bool
    {
        return (bool) update_post_meta($id, self::KEY, $data);
    }
}

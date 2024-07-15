<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\User;

/**
 * @internal
 *
 * @psalm-type RawItem = array{0: int<1, max>, 1: string}
 * @psalm-type RawData = array<array-key, RawItem>
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
     * @return RawData
     */
    public function read(int $id, string $key): array
    {
        $data = get_user_meta($id, $key, true);
        return  is_array($data) ? $data : [];
    }

    /**
     * @param RawData $data
     */
    public function write(int $id, string $key, array $data): bool
    {
        return (bool) update_user_meta($id, $key, $data);
    }
}

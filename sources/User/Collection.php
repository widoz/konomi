<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\User;

/**
 * @internal
 */
class Collection
{
    private static array|null $cache = null;

    public static function new(Meta\Read $meta, ItemFactory $itemFactory): Collection
    {
        return new self($meta, $itemFactory);
    }

    final private function __construct(
        readonly private Meta\Read $meta,
        readonly private ItemFactory $itemFactory
    ) {}

    public function items(User $user): array
    {
        if (self::$cache !== null) {
            return self::$cache;
        }

        foreach ($this->meta->read($user) as [$id, $type]) {
            self::$cache[$id] = $this->itemFactory->create($id, $type);
        }

        return self::$cache ?? [];
    }

    public function find(User $user, int $id): Item
    {

        self::$cache === null and self::$cache = $this->items($user);
        return self::$cache[$id] ?? NullItem::new();
    }
}

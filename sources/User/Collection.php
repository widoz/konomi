<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\User;

/**
 * @internal
 */
class Collection
{
    private static array|null $cache = null;

    public static function new(Meta\Read $data, ItemFactory $itemFactory): Collection
    {
        return new self($data, $itemFactory);
    }

    final private function __construct(
        readonly private Meta\Read $data,
        readonly private ItemFactory $itemFactory
    ) {}

    public function items(): ?array
    {
        if (self::$cache !== null) {
            return self::$cache;
        }

        foreach ($this->data->read() as [$id, $type]) {
            self::$cache[$id] = $this->itemFactory->create($id, $type);
        }

        return self::$cache;
    }

    public function find(int $id): ?Item
    {
        self::$cache === null and self::$cache = $this->items();
        return self::$cache[$id] ?? NullItem::new();
    }
}

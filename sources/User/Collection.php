<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\User;

class Collection
{
    private static array|null $cache = null;

    public static function new(User $user, Meta\Read $data, ItemFactory $itemFactory): Collection
    {
        return new self($user, $data, $itemFactory);
    }

    final private function __construct(
        readonly private User $user,
        readonly private Meta\Read $data,
        readonly private ItemFactory $itemFactory
    ) {}

    public function items(): ?array
    {
        if (!$this->user->isLoggedIn()) {
            return [];
        }
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

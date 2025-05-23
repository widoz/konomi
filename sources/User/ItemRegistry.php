<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\User;

/**
 * @internal
 * @phpstan-type Items = array<int, Item>
 * @phpstan-type Collection = \WeakMap<User, Items>
 */
class ItemRegistry
{
    public static function new(): self
    {
        /** @var Collection $items */
        $items = new \WeakMap();
        return new self($items);
    }

    /**
     * @param Collection $items
     */
    final private function __construct(
        private readonly \WeakMap $items
    ) {
    }

    public function hasGroup(User $user): bool
    {
        return $this->items->offsetExists($user);
    }

    public function has(User $user, Item $item): bool
    {
        if (!$this->hasGroup($user)) {
            return false;
        }

        return isset($this->items->offsetGet($user)[$item->id()]);
    }

    public function get(User $user, int $id): Item
    {
        if (!$this->hasGroup($user)) {
            return NullItem::new();
        }

        return $this->items->offsetGet($user)[$id] ?? NullItem::new();
    }

    public function set(User $user, Item $item): void
    {
        if (!$this->hasGroup($user)) {
            $this->items->offsetSet($user, []);
        }

        $collection = $this->items->offsetGet($user);
        $collection[$item->id()] = $item;

        $item->isValid() and $this->items->offsetSet($user, $collection);
    }

    public function unset(User $user, Item $item): void
    {
        if (!$this->has($user, $item)) {
            return;
        }

        $collection = $this->items->offsetGet($user);
        unset($collection[$item->id()]);
        $this->items->offsetSet($user, $collection);
    }

    /**
     * @return Items
     */
    public function all(User $user): array
    {
        return $this->hasGroup($user)
            ? $this->items->offsetGet($user)
            : [];
    }
}

<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\User;

/**
 * @internal
 */
class ItemCache
{
    public static function new(): self
    {
        return new self(new \WeakMap());
    }

    /**
     * @param \WeakMap<User, array<Item>> $items
     */
    final private function __construct(
        private \WeakMap $items
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

    public function all(User $user): array
    {
        return $this->hasGroup($user)
            ? $this->items->offsetGet($user)
            : [];
    }
}

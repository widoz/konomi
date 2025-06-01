<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\User;

/**
 * @internal
 * @phpstan-type Items = array<int, Item>
 * @phpstan-type Collection = array<string, Items>
 */
class ItemRegistry
{
    public static function new(ItemRegistryKey $itemRegistryKey): self
    {
        return new self($itemRegistryKey);
    }

    /**
     * @param Collection $items
     */
    final private function __construct(
        private readonly ItemRegistryKey $itemRegistryKey,
        private array $items = []
    ) {
    }

    public function hasGroup(User $user, ItemGroup $group): bool
    {
        $key = self::keyFor($user, $group);
        return isset($this->items["$key"]);
    }

    public function has(User $user, Item $item): bool
    {
        $key = self::keyFor($user, $item->group());

        if (!$this->hasGroup($user, $item->group())) {
            return false;
        }

        return isset($this->items["$key"][$item->id()]);
    }

    public function get(User $user, int $id, ItemGroup $group): Item
    {
        $nullItem = Item::null();
        $key = self::keyFor($user, $group);

        if (!$this->hasGroup($user, $group)) {
            return $nullItem;
        }

        return $this->items["$key"][$id] ?? $nullItem;
    }

    public function set(User $user, Item $item): void
    {
        $key = self::keyFor($user, $item->group());

        if (!$item->isValid()) {
            return;
        }

        // Avoid storing items with an empty key string.
        if (!$key) {
            return;
        }
        if (!$this->hasGroup($user, $item->group())) {
            $this->items["$key"] = [];
        }

        $collection = $this->items["$key"];
        $collection[$item->id()] = $item;

        $this->items["$key"] = $collection;
    }

    public function unset(User $user, Item $item): void
    {
        if (!$this->has($user, $item)) {
            return;
        }

        $key = self::keyFor($user, $item->group());
        $collection = $this->items["$key"];
        unset($collection[$item->id()]);
        $this->items["$key"] = $collection;
    }

    /**
     * @return Items
     */
    public function all(User $user, ItemGroup $group): array
    {
        $key = self::keyFor($user, $group);
        return $this->hasGroup($user, $group)
            ? $this->items["$key"]
            : [];
    }

    private function keyFor(User $user, ItemGroup $group): string
    {
        return $this->itemRegistryKey->for($user, $group);
    }
}

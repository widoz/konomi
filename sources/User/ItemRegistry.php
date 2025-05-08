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
    public static function new(): self
    {
        return new self();
    }

    /**
     * @param Collection $items
     */
    final private function __construct(
        private array $items = []
    ) {
    }

    public function hasGroup(User $user, ItemGroup $group): bool
    {
        $key = self::key($user, $group);
        return isset($this->items["$key"]);
    }

    public function has(User $user, Item $item): bool
    {
        $key = self::key($user, $item->group());
        if (!$this->hasGroup($user, $item->group())) {
            return false;
        }

        return isset($this->items["$key"][$item->id()]);
    }

    public function get(User $user, int $id, ItemGroup $group): Item
    {
        $nullItem = Item::null();
        $key = self::key($user, $group);

        if (!$this->hasGroup($user, $group)) {
            return $nullItem;
        }

        return $this->items["$key"][$id] ?? $nullItem;
    }

    public function set(User $user, Item $item): void
    {
        $key = self::key($user, $item->group());
        if (!$this->hasGroup($user, $item->group())) {
            $this->items["$key"] = [];
        }

        $collection = $this->items["$key"];
        $collection[$item->id()] = $item;

        $item->isValid() and $this->items["$key"] = $collection;
    }

    public function unset(User $user, Item $item): void
    {
        if (!$this->has($user, $item)) {
            return;
        }

        $key = self::key($user, $item->group());
        $collection = $this->items["$key"];
        unset($collection[$item->id()]);
        $this->items["$key"] = $collection;
    }

    /**
     * @return Items
     */
    public function all(User $user, ItemGroup $group): array
    {
        $key = self::key($user, $group);
        return $this->hasGroup($user, $group)
            ? $this->items["$key"]
            : [];
    }

    private static function key(User $user, ItemGroup $group): ItemRegistryKey
    {
        // TODO Must be a service with a factory.
        return ItemRegistryKey::new($user, $group);
    }
}

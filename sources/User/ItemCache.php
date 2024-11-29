<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\User;

/**
 * @internal
 */
final class ItemCache
{
    /**
     * @var array<Item>|null
     */
    private array|null $items = null;

    public static function new(): self
    {
        return new self();
    }

    final private function __construct()
    {
    }

    public function has(int $id): bool
    {
        return isset($this->items[$id]);
    }

    public function get(int $id): Item
    {
        return $this->items[$id] ?? NullItem::new();
    }

    public function set(int $id, Item $item): void
    {
        if ($item->isValid()) {
            $this->items[$id] = $item;
        }
    }

    public function all(): array
    {
        return $this->items ?? [];
    }

    public function clear(): void
    {
        $this->items = null;
    }
}

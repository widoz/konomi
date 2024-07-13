<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\User;

/**
 * @internal
 */
class Collection
{
    private static array|null $cache = null;

    public static function new(Meta $meta, ItemFactory $itemFactory): Collection
    {
        return new self($meta, $itemFactory);
    }

    final private function __construct(
        readonly private Meta $meta,
        readonly private ItemFactory $itemFactory
    ) {}

    public function find(User $user, string $key, int $id): Item
    {
        self::$cache === null and self::$cache = $this->items($user, $key);
        return self::$cache[$id] ?? NullItem::new();
    }

    public function save(User $user, string $key, Item $item): bool
    {
        $originalMeta = $this->meta->read($user->id(), $key) ?: [];
        $toStoreMeta = $originalMeta;

        if ($item->isActive()) {
            $toStoreMeta[$item->id()] = [$item->id(), $item->type()];
        } else {
            unset($toStoreMeta[$item->id()]);
        }

        if ($originalMeta === $toStoreMeta) {
            return true;
        }

        return $this->meta->write($user->id(), $key, $toStoreMeta);
    }

    /**
     * @return array<int, Item>
     */
    private function items(User $user, string $key): array
    {
        if (self::$cache !== null) {
            return self::$cache;
        }

        /**
         * @var $id int
         * @var $type string
         */
        // TODO Validate data to read by using a schema.
        foreach ($this->meta->read($user->id(), $key) as [$id, $type]) {
            self::$cache[$id] = $this->itemFactory->create($id, $type, true);
        }

        return self::$cache ?? [];
    }
}

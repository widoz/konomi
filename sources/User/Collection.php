<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\User;

/**
 * @internal
 */
class Collection
{
    private static array|null $cache = null;

    public static function new(Storage $storage, ItemFactory $itemFactory): Collection
    {
        return new self($storage, $itemFactory);
    }

    final private function __construct(
        readonly private Storage $storage,
        readonly private ItemFactory $itemFactory
    ) {
    }

    public function find(User $user, string $key, int $id): Item
    {
        self::$cache === null and self::$cache = $this->items($user, $key);
        $item = self::$cache[$id] ?? NullItem::new();

        do_action('konomi.user.collection.find', $item, $user, $key, $id);

        return $item;
    }

    public function save(User $user, string $key, Item $item): bool
    {
        $originalMeta = $this->storage->read($user->id(), $key) ?: [];
        $toStoreMeta = $originalMeta;

        unset($toStoreMeta[$item->id()]);
        if ($item->isActive()) {
            $toStoreMeta[$item->id()] = [$item->id(), $item->type()];
        }

        if ($originalMeta === $toStoreMeta) {
            return true;
        }

        do_action('konomi.user.collection.save', $item, $user, $key);

        return $this->storage->write($user->id(), $key, $toStoreMeta);
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
        foreach ($this->storage->read($user->id(), $key) as [$id, $type]) {
            self::$cache[$id] = $this->itemFactory->create($id, $type, true);
        }

        return self::$cache ?? [];
    }
}

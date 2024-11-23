<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\User;

/**
 * @internal
 */
class Collection
{
    /**
     * @var array<Item>|null
     */
    private static array|null $cache = null;

    public static function new(string $key, Storage $storage, ItemFactory $itemFactory): Collection
    {
        return new self($key, $storage, $itemFactory);
    }

    final private function __construct(
        readonly private string $key,
        readonly private Storage $storage,
        readonly private ItemFactory $itemFactory
    ) {
    }

    public function find(User $user, int $id): Item
    {
        self::$cache === null and self::$cache = $this->items($user);
        $item = self::$cache[$id] ?? NullItem::new();

        do_action('konomi.user.collection.find', $item, $user, $this->key, $id);

        return $item;
    }

    public function save(User $user, Item $item): bool
    {
        $userId = $user->id();

        if ($userId <= 0) {
            return false;
        }
        if (!$item->isValid()) {
            return false;
        }

        /** @var array<int, array{0: int, 1: string}> $storedData */
        $storedData = $this->storage->read($userId, $this->key) ?: [];
        $toStoreData = $storedData;

        unset($toStoreData[$item->id()]);
        if ($item->isActive()) {
            $item->id() > 0 and $toStoreData[$item->id()] = [$item->id(), $item->type()];
        }

        if ($storedData === $toStoreData) {
            return true;
        }

        do_action('konomi.user.collection.save', $item, $user, $this->key);

        return $this->storage->write($userId, $this->key, $toStoreData);
    }

    /**
     * @return array<Item>
     */
    private function items(User $user): array
    {
        if (self::$cache !== null) {
            return self::$cache;
        }

        foreach ($this->storage->read($user->id(), $this->key) as $rawItem) {
            if (!is_array($rawItem)) {
                continue;
            }

            $this->cacheItem($rawItem);
        }

        /** @psalm-suppress TypeDoesNotContainType */
        return self::$cache ?? [];
    }

    private function cacheItem(array $rawItem): void
    {
        $id = (int) ($rawItem[0] ?? null);
        $type = (string) ($rawItem[1] ?? null);

        $item = $this->itemFactory->create($id, $type, true);
        $item->isValid() and self::$cache[$id] = $item;
    }
}

<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\User;

/**
 * @internal
 * @psalm-type RawItem = array{0: int, 1: string}
 */
class Collection
{
    public static function new(
        string $key,
        Storage $storage,
        ItemFactory $itemFactory,
        ItemCache $cache
    ): Collection {
        return new self($key, $storage, $itemFactory, $cache);
    }

    final private function __construct(
        readonly private string $key,
        readonly private Storage $storage,
        readonly private ItemFactory $itemFactory,
        readonly private ItemCache $cache
    ) {
    }

    public function find(User $user, int $id): Item
    {
        $this->loadItems($user);
        $item = $this->cache->get($id);

        do_action('konomi.user.collection.find', $item, $user, $this->key, $id);

        return $item;
    }

    public function save(User $user, Item $item): bool
    {
        if (!$this->canSave($user, $item)) {
            return false;
        }

        $data = $this->read($user->id());
        $toStoreData = $this->prepareDataToStore($data, $item);

        if ($data === $toStoreData) {
            return true;
        }

        do_action('konomi.user.collection.save', $item, $user, $this->key);

        return $this->storage->write($user->id(), $this->key, $toStoreData);
    }

    /**
     * @return array<int, RawItem>
     */
    private function read(int $userId): array
    {
        $storedData = $this->storage->read($userId, $this->key) ?: [];
        $this->ensureDataStructure($storedData);
        return $storedData;
    }

    private function canSave(User $user, Item $item): bool
    {
        return $user->id() > 0 && $item->isValid();
    }

    /**
     * @param array<int, RawItem> $data
     * @return array<int, RawItem>
     */
    private function prepareDataToStore(array $data, Item $item): array
    {
        $toStoreData = $data;
        unset($toStoreData[$item->id()]);

        if ($item->isActive() && $item->id() > 0) {
            $toStoreData[$item->id()] = [$item->id(), $item->type()];
        }

        return $toStoreData;
    }

    /**
     * @psalm-assert array<int, RawItem> $data
     */
    private function ensureDataStructure(array &$data): void
    {
        foreach ($data as $id => $item) {
            if (!is_array($item)
                || count($item) !== 2
                || !isset($item[0], $item[1])
                || !is_int($item[0])
                || !is_string($item[1])
            ) {
                unset($data[$id]);
            }
        }
    }

    /**
     * @return array<Item>
     */
    private function loadItems(User $user): array
    {
        if ($this->cache->all()) {
            return $this->cache->all();
        }

        foreach ($this->read($user->id()) as $rawItem) {
            if (!is_array($rawItem)) {
                continue;
            }

            $this->cacheItem($rawItem);
        }

        return $this->cache->all();
    }

    private function cacheItem(array $rawItem): void
    {
        $id = (int) ($rawItem[0] ?? null);
        $type = (string) ($rawItem[1] ?? null);

        $item = $this->itemFactory->create($id, $type, true);
        $this->cache->set($id, $item);
    }
}

<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\User;

/**
 * @internal
 * @psalm-type RawItem = array{0: int, 1: string}
 * TODO This is a Repository pattern, we should rename it
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

        $this->loadItems($user);
        $data = $this->cache->all();
        $toStoreData = $this->prepareDataToStore($data, $item);

        if ($data === $toStoreData) {
            return true;
        }

        do_action('konomi.user.collection.save', $item, $user, $this->key);

        return $this->storage->write($user->id(), $this->key, $toStoreData);
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

        if (!$item->isActive()) {
            unset($toStoreData[$item->id()]);
            return $toStoreData;
        }

        $toStoreData[$item->id()] = [$item->id(), $item->type()];

        return $toStoreData;
    }

    /**
     * @return array<Item>
     */
    private function loadItems(User $user): array
    {
        if ($this->cache->all()) {
            return $this->cache->all();
        }

        foreach ($this->read($user->id()) as [$id, $type]) {
            $item = $this->itemFactory->create($id, $type, true);
            // TODO Cache need a Group for the user, we have to serve multiple users with
            //      a single service.
            $item->isValid() and $this->cache->set($id, $item);
        }

        return $this->cache->all();
    }

    /**
     * @param int $userId
     * @return \Generator
     */
    private function read(int $userId): \Generator
    {
        $storedData = $this->storage->read($userId, $this->key) ?: [];
        yield from $this->ensureDataStructure($storedData);
    }

    /**
     * @psalm-assert array<int, RawItem> $data
     */
    private function ensureDataStructure(array $data): \Generator
    {
        foreach ($data as $id => $item) {
            if (!is_array($item)
                || count($item) !== 2
                || !isset($item[0], $item[1])
                || !is_int($item[0])
                || !is_string($item[1])
            ) {
                continue;
            }

            yield $id => $item;
        }
    }
}

<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\User;

/**
 * @internal
 * @psalm-type RawItem = array{0: int, 1: string}
 */
class Repository
{
    public static function new(
        string $key,
        Storage $storage,
        ItemFactory $itemFactory,
        ItemCache $cache
    ): Repository {

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
        $item = $this->cache->get($user, $id);

        do_action('konomi.user.repository.find', $item, $user, $this->key, $id);

        return $item;
    }

    public function save(User $user, Item $item): bool
    {
        if (!$this->canSave($user, $item)) {
            return false;
        }

        $this->loadItems($user);
        $dataToStore = $this->prepareDataToStore($user, $item);

        do_action('konomi.user.repository.save', $item, $user, $this->key);

        return $this->storage->write($user->id(), $this->key, $dataToStore);
    }

    private function canSave(User $user, Item $item): bool
    {
        return $user->id() > 0 && $item->isValid();
    }

    /**
     * @param array<int, RawItem> $data
     * @return array<int, RawItem>
     */
    private function prepareDataToStore(User $user, Item $item): array
    {
        if (!$item->isActive()) {
            $this->cache->unset($user, $item);
            return $this->serializeData($user);
        }

        $this->cache->set($user, $item);
        return $this->serializeData($user);
    }

    private function serializeData(User $user): array
    {
        return \array_map(
            static fn (Item $item) => [$item->id(), $item->type()],
            $this->cache->all($user)
        );
    }

    /**
     * @return array<Item>
     */
    private function loadItems(User $user): void
    {
        if ($this->cache->hasGroup($user)) {
            return;
        }

        foreach ($this->read($user->id()) as [$id, $type]) {
            $item = $this->itemFactory->create($id, $type, true);
            $item->isValid() and $this->cache->set($user, $item);
        }
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
            if (
                !is_array($item)
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

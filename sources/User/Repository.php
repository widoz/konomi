<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\User;

/**
 * @internal
 *
 * @psalm-type EntityId = int
 * @psalm-type EntityType = string
 * @psalm-type RawItem = array{0: EntityId, 1: EntityType}
 * @psalm-type RawItems = array<array-key, RawItem>
 */
class Repository
{
    public static function new(
        string $key,
        Storage $storage,
        ItemFactory $itemFactory,
        ItemCache $cache,
        RawDataAssert $rawDataAsserter
    ): Repository {

        return new self($key, $storage, $itemFactory, $cache, $rawDataAsserter);
    }

    final private function __construct(
        readonly private string $key,
        readonly private Storage $storage,
        readonly private ItemFactory $itemFactory,
        readonly private ItemCache $cache,
        readonly private RawDataAssert $rawDataAsserter
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
     * @return RawItems
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

    /**
     * @return RawItems
     */
    private function serializeData(User $user): array
    {
        return \array_map(
            static fn (Item $item) => [$item->id(), $item->type()],
            $this->cache->all($user)
        );
    }

    private function loadItems(User $user): void
    {
        if ($this->cache->hasGroup($user)) {
            return;
        }

        foreach ($this->read($user->id()) as [$entityId, $entityType]) {
            $item = $this->itemFactory->create($entityId, $entityType, true);
            $item->isValid() and $this->cache->set($user, $item);
        }
    }

    /**
     * @return \Generator<RawItem>
     */
    private function read(int $userId): \Generator
    {
        $storedData = $this->storage->read($userId, $this->key);
        yield from $this->rawDataAsserter->ensureDataStructure($storedData);
    }
}

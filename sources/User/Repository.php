<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\User;

/**
 * @internal
 *
 * @phpstan-type EntityId = int
 * @phpstan-type EntityType = string
 * @phpstan-type RawItem = array{0: EntityId, 1: EntityType}
 * @phpstan-type RawItems = array<array-key, RawItem>
 */
class Repository
{
    public static function new(
        string $key,
        Storage $storage,
        ItemFactory $itemFactory,
        ItemRegistry $registry,
        RawDataAssert $rawDataAsserter
    ): Repository {

        return new self($key, $storage, $itemFactory, $registry, $rawDataAsserter);
    }

    final private function __construct(
        readonly private string $key,
        readonly private Storage $storage,
        readonly private ItemFactory $itemFactory,
        readonly private ItemRegistry $registry,
        readonly private RawDataAssert $rawDataAsserter
    ) {
    }

    public function find(User $user, int $id): Item
    {
        $this->loadItems($user);
        $item = $this->registry->get($user, $id);

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
        $item->isActive()
            ? $this->registry->set($user, $item)
            : $this->registry->unset($user, $item);

        return $this->serializeData($user);
    }

    /**
     * @return RawItems
     */
    private function serializeData(User $user): array
    {
        return \array_map(
            static fn (Item $item) => [$item->id(), $item->type()],
            $this->registry->all($user)
        );
    }

    private function loadItems(User $user): void
    {
        if ($this->registry->hasGroup($user)) {
            return;
        }

        foreach ($this->read($user) as [$entityId, $entityType]) {
            $item = $this->itemFactory->create($entityId, $entityType, true);
            $item->isValid() and $this->registry->set($user, $item);
        }
    }

    /**
     * @return \Generator<RawItem>
     */
    private function read(User $user): \Generator
    {
        $storedData = $this->storage->read($user->id(), $this->key);
        yield from $this->rawDataAsserter->ensureDataStructure($storedData);
    }
}

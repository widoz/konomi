<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Post;

use Widoz\Wp\Konomi\User;

/**
 * @internal
 *
 * @psalm-type EntityId = int
 * @psalm-type EntityType = string
 * @psalm-type UserId = int
 * @psalm-type RawItem = array{0: EntityId, 1: EntityType}
 * @psalm-type RawItems = array<UserId, RawItem>
 * @psalm-type StoredData = array<EntityId, RawItems>
 *
 * TODO This Collection require caching the data from the Storage
 */
class Repository
{
    public static function new(
        string $key,
        Storage $storage,
        User\ItemFactory $itemFactory
    ): Repository {

        return new self($key, $storage, $itemFactory);
    }

    final private function __construct(
        readonly private string $key,
        readonly private Storage $storage,
        readonly private User\ItemFactory $itemFactory,
    ) {
    }

    /**
     * @param int $entityId
     * @return array<int, array<User\Item>>
     */
    public function find(int $entityId): array
    {
        $result = [];
        foreach ($this->read($entityId) as $userId => $rawItems) {
            $result[$userId] = $this->unserialize($rawItems);
        }
        return $result;
    }

    /**
     * @psalm-suppress PossiblyUnusedReturnValue Isn't up to use to decide that.
     */
    public function save(User\Item $item, User\User $user): bool
    {
        if (!$item->isValid()) {
            return false;
        }

        $data = iterator_to_array($this->read($item->id()));
        $toStoreData = $this->prepareDataToStore($data, $item, $user);

        if ($data === $toStoreData) {
            return true;
        }

        do_action('konomi.post.collection.save', $item, $user, $this->key);

        return $this->storage->write($item->id(), $this->key, $toStoreData);
    }

    /**
     * @param StoredData $data
     */
    private function prepareDataToStore(array $data, User\Item $item, User\User $user): array
    {
        $data[$user->id()] ??= [];

        self::has($item, $data, $user)
            ? self::removeItem($item, $data, $user)
            : self::addItem($item, $data, $user);

        return $data;
    }

    /**
     * @return StoredData
     */
    private function read(int $entityId): \Generator
    {
        $storedData = $this->storage->read($entityId, $this->key);
        yield from $this->ensureDataStructure($storedData);
    }

    /**
     * @psalm-assert StoredData $storedData
     */
    private function ensureDataStructure(array $data): \Generator
    {
        foreach ($data as $userId => &$rawItems) {
            if (!is_int($userId) || $userId < 1) {
                continue;
            }
            if (!is_array($rawItems)) {
                continue;
            }

            $rawItems = array_filter(
                $rawItems,
                [$this, 'ensureRawItemsDataStructure']
            );

            if (!empty($rawItems)) {
                yield $userId => $rawItems;
            }
        }
    }

    private function ensureRawItemsDataStructure(array $data): \Generator
    {
        foreach ($data as $id => $item) {
            if (
                !is_array($item)
                || count($item) !== 2
                || !isset($item[0], $item[1])
                || (!is_int($item[0]) || $item[0] < 1)
                || (!is_string($item[1]) || $item[1] === '')
            ) {
                continue;
            }

            yield $id => $item;
        }
    }

    /**
     * @param StoredData $data
     */
    private static function has(User\Item $item, array $data, User\User $user): bool
    {
        return in_array([$item->id(), $item->type()], $data[$user->id()], true);
    }

    /**
     * @param StoredData $data
     */
    private static function removeItem(User\Item $item, array &$data, User\User $user): void
    {
        $data[$user->id()] = array_filter(
            $data[$user->id()],
            static fn (array $entry) => $entry[0] !== $item->id() && $entry[1] !== $item->type()
        );
    }

    /**
     * @param StoredData $data
     */
    private static function addItem(User\Item $item, array &$data, User\User $user): void
    {
        $data[$user->id()] = [
            [$item->id(), $item->type()],
        ];
    }

    /**
     * @param RawItems $rawItems
     */
    private function unserialize(array $rawItems): array
    {
        $items = [];
        foreach ($rawItems as $rawItem) {
            if (!is_array($rawItem)) {
                continue;
            }

            $id = (int) ($rawItem[0] ?? null);
            $type = (string) ($rawItem[1] ?? null);

            $items[] = $this->itemFactory->create($id, $type, true);
        }
        return $items;
    }
}

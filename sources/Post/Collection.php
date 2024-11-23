<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Post;

use Widoz\Wp\Konomi\User;

/**
 * @internal
 *
 * @psalm-type RawItem = array{0: int, 1: string}
 * @psalm-type RawItems = array<int, RawItem>
 * @psalm-type StoredData = array<int, RawItems>
 *
 * TODO This Collection require caching the data from the Storage
 */
class Collection
{
    public static function new(
        string $key,
        Storage $storage,
        User\ItemFactory $itemFactory
    ): Collection {

        return new self($key, $storage, $itemFactory);
    }

    final private function __construct(
        readonly private string $key,
        readonly private Storage $storage,
        readonly private User\ItemFactory $itemFactory,
    ) {
    }

    public function find(int $entityId): array
    {
        $data = $this->storage->read($entityId, $this->key);
        $collector = [];

        foreach ($data as $userId => $rawItems) {
            if (!is_array($rawItems)) {
                continue;
            }

            $collector[$userId] = $this->unserialize($rawItems);
        }

        return $collector;
    }

    /**
     * @psalm-suppress PossiblyUnusedReturnValue Isn't up to use to decide that.
     */
    public function save(User\Item $item, User\User $user): bool
    {
        if (!$item->isValid()) {
            return false;
        }

        $postId = $item->id();
        $storedData = $this->storage->read($postId, $this->key);

        $this->ensureDataStructure($storedData, $user);

        if (self::has($item, $storedData, $user)) {
            self::removeItem($item, $storedData, $user);
            return $this->storage->write($postId, $this->key, $storedData);
        }

        self::addItem($item, $storedData, $user);
        return $this->storage->write($postId, $this->key, $storedData);
    }

    /**
     * @psalm-assert StoredData $storedData
     */
    private function ensureDataStructure(array &$data, User\User $user): void
    {
        if (!isset($data[$user->id()])) {
            $data[$user->id()] = [];
        }

        foreach ($data as $userId => $rawItems) {
            if (!is_array($rawItems)) {
                unset($data[$userId]);
                continue;
            }

            // Ensure each item in the array has correct structure
            $data[$userId] = array_filter(
                $rawItems,
                static fn ($item): bool => is_array($item)
                    && count($item) === 2
                    && isset($item[0], $item[1])
                    && is_int($item[0])
                    && is_string($item[1])
            );
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
            ...$data[$user->id()],
            // TODO Let the Item to serialize the data.
            [$item->id(), $item->type()],
        ];
    }

    private function unserialize(array $rawItems): array
    {
        $items = [];
        foreach ($rawItems as $rawItem) {
            if (!is_array($rawItem)) {
                continue;
            }

            $id = (int) ($rawItem[0] ?? null);
            $type = (string) ($rawItem[1] ?? null);

            // TODO If the item serialize it self, it also unserialize it self.
            $items[] = $this->itemFactory->create($id, $type, true);
        }
        return $items;
    }
}

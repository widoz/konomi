<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Post;

use Widoz\Wp\Konomi\User;

/**
 * @internal
 *
 * @psalm-type ItemsCollection = array<int<1, max>, array<User\Item>>
 * @psalm-import-type RawData from Storage
 *
 * TODO This Collection require caching the data from the Storage
 */
class Collection
{
    public static function new(Storage $storage, User\ItemFactory $itemFactory): Collection
    {
        return new self($storage, $itemFactory);
    }

    final private function __construct(
        readonly private Storage $storage,
        readonly private User\ItemFactory $itemFactory,
    ) {
    }

    /**
     * @return ItemsCollection
     */
    public function find(int $id): array
    {
        $data = $this->storage->read($id);
        $collector = [];

        foreach ($data as $userId => $items) {
            $collector[$userId] = array_map(
                fn (array $item) => $this->itemFactory->create($item[0], $item[1], true),
                $items
            );
        }

        return $collector;
    }

    /**
     * @return array<array-key, User\Item>
     */
    public function findForUser(int $id, User\User $user): array
    {
        $data = $this->find($id);
        return $data[$user->id()] ?? [];
    }

    public function save(User\Item $item, User\User $user): bool
    {
        $postId = $item->id();
        $storedData = $this->storage->read($postId);
        $userData = $storedData[$user->id()] ?? [];

        if (self::has($item, $userData)) {
            $storedData = $this->removeItem($item, $userData);
            return $this->storage->write($postId, $storedData);
        }

        $storedData[$user->id()] = [
            ...$userData,
            [$item->id(), $item->type()],
        ];

        return $this->storage->write($postId, $storedData);
    }

    private function removeItem(User\Item $item, array $userData): array
    {
        return array_filter(
            $userData,
            static fn (array $entry) => $entry[0] !== $item->id() && $entry[1] !== $item->type()
        );
    }

    /**
     * @param RawData $dbData
     */
    private static function has(User\Item $item, array $dbData): bool
    {
        foreach ($dbData as $entry) {
            if ($entry[0] === $item->id() && $entry[1] === $item->type()) {
                return true;
            }
        }

        return false;
    }
}

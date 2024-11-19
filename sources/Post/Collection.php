<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Post;

use Widoz\Wp\Konomi\User;

/**
 * @internal
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

    public function find(int $id): array
    {
        $data = $this->storage->read($id);
        $collector = [];

        foreach ($data as $userId => $rawItems) {
            if (!is_array($rawItems)) {
                continue;
            }

            $collector[$userId] = $this->mapRawToItem($rawItems);
        }

        return $collector;
    }

    private function mapRawToItem(array $rawItems): array
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

    /**
     * @psalm-suppress PossiblyUnusedReturnValue Isn't up to use to decide that.
     */
    public function save(User\Item $item, User\User $user): bool
    {
        if (!$item->isValid()) {
            return false;
        }

        $postId = $item->id();
        $storedData = $this->storage->read($postId);
        $userData = $storedData[$user->id()] ?? [];

        if (!is_array($userData)) {
            return false;
        }

        if (self::has($item, $userData)) {
            $storedData[$user->id()] = $this->removeItem($item, $userData);
            return $this->storage->write($postId, $storedData);
        }

        $storedData[$user->id()] = [
            ...$userData,
            // TODO Let the Item to serialize the data.
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

    private static function has(User\Item $item, array $dbData): bool
    {
        return in_array([$item->id(), $item->type()], $dbData, true);
    }
}

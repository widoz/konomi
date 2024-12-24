<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Post;

/**
 * @internal
 *
 * @psalm-type EntityId = int
 * @psalm-type EntityType = string
 * @psalm-type UserId = int
 * @psalm-type RawItem = array{0: EntityId, 1: EntityType}
 * @psalm-type RawItems = array<array-key, RawItem>
 * @psalm-type StoredData = array<UserId, RawItems>
 * @psalm-type GeneratorStoredData = \Generator<UserId, RawItems>
 */
final class RawDataValidator
{
    public static function new(): RawDataValidator
    {
        return new self();
    }

    final private function __construct()
    {
    }

    /**
     * @return GeneratorStoredData
     */
    public function ensureDataStructure(array $data): \Generator
    {
        foreach ($data as $userId => $rawItems) {
            if (!self::isValidUserId($userId)) {
                continue;
            }
            if (!self::areRawItemsValid($rawItems)) {
                continue;
            }

            yield $userId => $rawItems;
        }
    }

    /**
     * @psalm-assert UserId $userId
     */
    private static function isValidUserId(mixed $userId): bool
    {
        return is_int($userId) && $userId > 0;
    }

    /**
     * @psalm-assert RawItems $rawItems
     */
    private static function areRawItemsValid(mixed $rawItems): bool
    {
        if (!is_array($rawItems)) {
            return false;
        }
        if (!self::isValidRawItem($rawItems[0] ?? [])) {
            return false;
        }

        return true;
    }

    /**
     * @psalm-assert RawItem $item
     */
    private static function isValidRawItem(mixed $rawItem): bool
    {
        return is_array($rawItem)
            && count($rawItem) === 2
            && isset($rawItem[0], $rawItem[1])
            && is_int($rawItem[0])
            && $rawItem[0] > 0
            && is_string($rawItem[1])
            && $rawItem[1] !== '';
    }
}

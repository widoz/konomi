<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Post;

/**
 * @internal
 * @phpstan-type EntityId = int
 * @phpstan-type EntityType = string
 * @phpstan-type UserId = int
 * @phpstan-type RawItem = array{0: EntityId, 1: EntityType}
 * @phpstan-type RawItems = array<array-key, RawItem>
 * @phpstan-type StoredData = array<UserId, RawItems>
 * @phpstan-type GeneratorStoredData = \Generator<UserId, RawItems>
 */
class RawDataAssert
{
    public static function new(): RawDataAssert
    {
        return new self();
    }

    final private function __construct()
    {
    }

    /**
     * @param array<mixed> $data
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
     * @phpstan-assert-if-true UserId $userId
     */
    private static function isValidUserId(mixed $userId): bool
    {
        return is_int($userId) && $userId > 0;
    }

    /**
     * @phpstan-assert-if-true RawItems $rawItems
     * @param mixed $rawItems
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
     * @phpstan-assert-if-true RawItem $rawItem
     */
    private static function isValidRawItem(mixed $rawItem): bool
    {
        return is_array($rawItem)
            && count($rawItem) === 3
            && isset($rawItem[0], $rawItem[1], $rawItem[2])
            && is_int($rawItem[0])
            && $rawItem[0] > 0
            && is_string($rawItem[1])
            && $rawItem[1] !== ''
            && is_string($rawItem[2])
            && $rawItem[2] !== '';
    }
}

<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Post;

/**
 * @internal
 *
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

    private static function isValidUserId(mixed $userId): bool
    {
        return is_int($userId) && $userId > 0;
    }

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

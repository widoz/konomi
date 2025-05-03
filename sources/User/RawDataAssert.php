<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\User;

/**
 * @internal
 * @phpstan-type RawItem = array{0: int, 1: string}
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
     * @param array<mixed> $rawItems
     * @return \Generator<array-key, RawItem>
     */
    public function ensureDataStructure(array $rawItems): \Generator
    {
        foreach ($rawItems as $item) {
            self::isValidRawItem($item) and yield $item;
        }
    }

    /**
     * @phpstan-assert-if-true RawItem $rawItem
     */
    private static function isValidRawItem(mixed $rawItem): bool
    {
        return is_array($rawItem)
            && count($rawItem) === 3
            && isset($rawItem[0], $rawItem[1])
            && is_int($rawItem[0])
            && $rawItem[0] > 0
            && is_string($rawItem[1])
            && $rawItem[1] !== ''
            && is_string($rawItem[2])
            && $rawItem[2] !== '';
    }
}

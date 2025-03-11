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
     * @return \Generator<array-key, RawItem>
     */
    public function ensureDataStructure(array $rawItems): \Generator
    {
        foreach ($rawItems as $item) {
            if (!self::isValidRawItem($item)) {
                continue;
            }

            yield $item;
        }
    }

    /**
     * @phpstan-assert RawItem $rawItem
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

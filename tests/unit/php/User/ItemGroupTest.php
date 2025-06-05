<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Tests\Unit\User;

use Brain\Monkey\Functions;
use Widoz\Wp\Konomi\User\ItemGroup;

beforeEach(function (): void {
    Functions\when('esc_html')->returnArg();
});

describe('ItemGroup', function (): void {
    it('has the correct case values', function (): void {
        expect(ItemGroup::REACTION->value)->toBe('reaction');
        expect(ItemGroup::BOOKMARK->value)->toBe('bookmark');
    });
});

describe('fromValue', function (): void {
    it('returns the same instance when given an ItemGroup', function (): void {
        $group = ItemGroup::REACTION;
        expect(ItemGroup::fromValue($group))->toBe($group);
    });

    it('returns the correct ItemGroup when given a valid string value', function (): void {
        expect(ItemGroup::fromValue('reaction'))->toBe(ItemGroup::REACTION);
        expect(ItemGroup::fromValue('bookmark'))->toBe(ItemGroup::BOOKMARK);
    });

    it('throws ValueError when given an invalid value', function (): void {
        expect(fn () => ItemGroup::fromValue('invalid'))
            ->toThrow(\ValueError::class, 'invalid is not a valid backing value for enum Widoz\Wp\Konomi\User\ItemGroup');
    });
});

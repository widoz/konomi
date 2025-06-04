<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Tests\Unit\User;

use Mockery;
use Widoz\Wp\Konomi\User\{
    ItemGroup,
    ItemRegistryKey,
    User
};

describe('ItemRegistryKey', function (): void {
    it('can be instantiated via static factory method', function (): void {
        $key = ItemRegistryKey::new();
        expect($key)->toBeInstanceOf(ItemRegistryKey::class);
    });
});

describe('for', function (): void {
    it(
        'throw error when cannot generate the key because of user is missing',
        function (): void {
            $user = Mockery::mock(User::class);
            $user->shouldReceive('id')->andReturn(0);
            ItemRegistryKey::new()->for($user, ItemGroup::REACTION);
        }
    )->expectException(\UnexpectedValueException::class);

    it('returns a string combining user id and group value', function (): void {
        $user = Mockery::mock(User::class);
        $user->shouldReceive('id')->andReturn(123);
        $key = ItemRegistryKey::new();
        expect($key->for($user, ItemGroup::REACTION))->toBe('123.reaction');
    });
});

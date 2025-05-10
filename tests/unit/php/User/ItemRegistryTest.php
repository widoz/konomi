<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Tests\Unit\User;

use Widoz\Wp\Konomi\User\{
    ItemGroup,
    ItemRegistryKey,
    ItemRegistry,
    User,
    Item,
};
use Mockery;

beforeEach(function (): void {
    $itemRegistryKey = ItemRegistryKey::new();
    $this->registry = ItemRegistry::new($itemRegistryKey);
    $this->user = Mockery::mock(User::class);
});

function expectToBeInvalid(mixed $item): void
{
    expect($item->group())->toBe(ItemGroup::REACTION);
    expect($item->id())->toBe(0);
    expect($item->isValid())->toBeFalse();
}

describe('hasGroup', function (): void {
    it('checks if user has items group', function (): void {
        $this->user->shouldReceive('id')->andReturn(1);
        expect($this->registry->hasGroup($this->user, ItemGroup::REACTION))->toBeFalse();

        $item = Item::new(10, 'post', true);
        $this->registry->set($this->user, $item);
        expect($this->registry->hasGroup($this->user, ItemGroup::REACTION))->toBeTrue();
    });
});

describe('has', function (): void {
    it('checks if user has specific item', function (): void {
        $this->user->shouldReceive('id')->andReturn(1);
        $item = Item::new(10, 'post', true);

        expect($this->registry->has($this->user, $item))->toBeFalse();
        $this->registry->set($this->user, $item);
        expect($this->registry->has($this->user, $item))->toBeTrue();
    });
});

describe('get', function (): void {
    it('gets null item when user has no items', function (): void {
        $this->user->shouldReceive('id')->andReturn(1);
        $item = $this->registry->get($this->user, 1, ItemGroup::REACTION);
        expectToBeInvalid($item);
    });

    it(
        'return null item object if the given Item ID does not exists for the user',
        function (): void {
            $this->user->shouldReceive('id')->andReturn(1);
            $item = Item::new(1, 'post', true);
            $this->registry->set($this->user, $item);
            expectToBeInvalid($this->registry->get($this->user, 2, ItemGroup::REACTION));
        }
    );
});

describe('set', function (): void {
    it('sets and gets item for user', function (): void {
        $this->user->shouldReceive('id')->andReturn(1);
        $item = Item::new(1, 'post', true);
        $this->registry->set($this->user, $item);
        expect($this->registry->has($this->user, $item))->toBeTrue();
    });
});

describe('unset', function (): void {
    it('unsets item for user', function (): void {
        $this->user->shouldReceive('id')->andReturn(1);
        $item = Item::new(1, 'post', true);
        $this->registry->set($this->user, $item);
        expect($this->registry->has($this->user, $item))->toBeTrue();
        $this->registry->unset($this->user, $item);
        expect($this->registry->has($this->user, $item))->toBeFalse();
    });

    it('do not unset an item if not present in the cache', function (): void {
        $this->user->shouldReceive('id')->andReturn(1);
        $item = Item::new(1, 'post', true);
        $this->registry->unset($this->user, $item);
        expect($this->registry->has($this->user, $item))->toBeFalse();
    });
});

describe('all', function (): void {
    it('gets all items for user', function (): void {
        $this->user->shouldReceive('id')->andReturn(1);
        expect($this->registry->all($this->user, ItemGroup::REACTION))->toBeArray()->toBeEmpty();
        for($count = 1; $count <= 10; $count++) {
            $item = Item::new($count, 'post', true);
            $this->registry->set($this->user, $item);
        }
        expect($this->registry->all($this->user, ItemGroup::REACTION))->toBeArray()->toHaveCount(10);
    });
});

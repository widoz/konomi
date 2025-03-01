<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Tests\Unit\User;

use Widoz\Wp\Konomi\User\ItemRegistry;
use Widoz\Wp\Konomi\User\NullItem;
use Widoz\Wp\Konomi\User\User;
use Widoz\Wp\Konomi\User\Item;
use Mockery;

beforeEach(function (): void {
    $this->registry = ItemRegistry::new();
    $this->user = Mockery::mock(User::class);
    $this->item = Mockery::mock(Item::class);
});

describe('ItemRegistry', function (): void {
    describe('hasGroup', function (): void {
        it('checks if user has items group', function (): void {
            expect($this->registry->hasGroup($this->user))->toBeFalse();
        });
    });

    describe('has', function (): void {
        it('checks if user has specific item', function (): void {
            $this->item->shouldReceive('id')->andReturn(1);
            expect($this->registry->has($this->user, $this->item))->toBeFalse();
        });
    });

    describe('get', function (): void {
        it('gets null item when user has no items', function (): void {
            expect($this->registry->get($this->user, 1))->toBeInstanceOf(NullItem::class);
        });

        it(
            'return null item object if the given Item ID does not exists for the user',
            function (): void {
                $this->item->shouldReceive('id')->andReturn(1);
                $this->item->shouldReceive('isValid')->andReturn(true);

                $this->registry->set($this->user, $this->item);
                expect($this->registry->get($this->user, 2))->toBeInstanceOf(NullItem::class);
            }
        );
    });

    describe('set', function (): void {
        it('sets and gets item for user', function (): void {
            $this->item->shouldReceive('id')->andReturn(1);
            $this->item->shouldReceive('isValid')->andReturn(true);

            $this->registry->set($this->user, $this->item);
            expect($this->registry->has($this->user, $this->item))->toBeTrue();
        });
    });

    describe('unset', function (): void {
        it('unsets item for user', function (): void {
            $this->item->shouldReceive('id')->andReturn(1);
            $this->item->shouldReceive('isValid')->andReturn(true);

            $this->registry->set($this->user, $this->item);
            $this->registry->unset($this->user, $this->item);
            expect($this->registry->has($this->user, $this->item))->toBeFalse();
        });

        it('do not unset an item if not present in the cache', function (): void {
            $this->item->shouldReceive('id')->andReturn(1);
            $this->item->shouldReceive('isValid')->andReturn(true);

            $this->registry->unset($this->user, $this->item);
            expect($this->registry->has($this->user, $this->item))->toBeFalse();
        });
    });

    describe('all', function (): void {
        it('gets all items for user', function (): void {
            expect($this->registry->all($this->user))->toBeArray()->toBeEmpty();

            $this->item->shouldReceive('id')->andReturn(1);
            $this->item->shouldReceive('isValid')->andReturn(true);

            $this->registry->set($this->user, $this->item);
            expect($this->registry->all($this->user))->toBeArray()->toHaveCount(1);
        });
    });
});
